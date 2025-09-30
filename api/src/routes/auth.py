from fastapi.security import OAuth2PasswordBearer
from sqlmodel import Session, select
from fastapi import Depends, APIRouter, HTTPException, BackgroundTasks
from ..schemas.auth import ForgotPasswordRequest, ResetPasswordRequest, LoginRequest
from ..core.security import (
    verify_password,
    create_access_token,
    create_refresh_token,
    decode_access_token,
    create_reset_password_token,
    verify_reset_password_token,
    get_password_hash,
)
from ..core.database import get_session
from ..models.user import User
from ..models.token_blacklist import TokenBlacklist
from ..schemas.auth import Token, RefreshTokenRequest

router = APIRouter(
    prefix="/auths",
    tags=["Autenticación"],
    responses={404: {"description": "Not found"}},
)

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/auth/login")


# Simulación de envío de correo (reemplaza por fastapi-mail en producción)
def send_reset_email(email: str, token: str):
    print(f"Enviando email a {email} con el token: {token}")


@router.post("/login", response_model=Token)
def login(form_data: LoginRequest, session: Session = Depends(get_session)):
    user = session.exec(select(User).where(User.email == form_data.email)).first()
    if not user or not verify_password(form_data.password, user.hashed_password):
        raise HTTPException(status_code=401, detail="Incorrect email or password")
    access_token = create_access_token(data={"sub": user.email})
    refresh_token = create_refresh_token(data={"sub": user.email})
    return {
        "statusCode": 200,
        "data": {
            "id": user.id,
            "email": user.email,
            "username": user.username,
        },
        "token": {
            "access_token": access_token,
            "refresh_token": refresh_token,
            "token_type": "bearer",
        },
    }


@router.post("/logout")
def logout(request: RefreshTokenRequest, session: Session = Depends(get_session)):
    # Add refresh_token to blacklist
    blacklist_entry = TokenBlacklist(token=request.refresh_token)
    session.add(blacklist_entry)
    session.commit()
    return {"message": "Logged out successfully", "statusCode": 200}


@router.post("/forgot-password")
def forgot_password(
    request: ForgotPasswordRequest,
    background_tasks: BackgroundTasks,
    session: Session = Depends(get_session),
):
    user = session.exec(select(User).where(User.email == request.email)).first()
    if user:
        token = create_reset_password_token(user.email)
        background_tasks.add_task(send_reset_email, user.email, token)
    return {
        "message": "recibirás instrucciones para restablecer tu contraseña.",
        "statusCode": 200,
    }


@router.post("/reset-password")
def reset_password(
    request: ResetPasswordRequest, session: Session = Depends(get_session)
):
    email = verify_reset_password_token(request.token)
    if not email:
        raise HTTPException(status_code=400, detail="Token inválido o expirado")
    user = session.exec(select(User).where(User.email == email)).first()
    if not user:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")
    user.hashed_password = get_password_hash(request.new_password)
    session.add(user)
    session.commit()
    return {"message": "Contraseña restablecida correctamente", "statusCode": 200}


@router.post("/refresh", response_model=Token)
def refresh_token(
    request: RefreshTokenRequest, session: Session = Depends(get_session)
):
    # Check if refresh_token is blacklisted
    blacklisted = session.exec(
        select(TokenBlacklist).where(TokenBlacklist.token == request.refresh_token)
    ).first()
    if blacklisted:
        raise HTTPException(status_code=401, detail="Refresh token revoked")

    # Decode refresh_token
    payload = decode_access_token(
        request.refresh_token
    )  # Use same decode since same key
    if not payload or "sub" not in payload:
        raise HTTPException(status_code=401, detail="Invalid refresh token")

    user = session.exec(select(User).where(User.email == payload["sub"])).first()
    if not user:
        raise HTTPException(status_code=401, detail="User not found")

    # Create new tokens
    access_token = create_access_token(data={"sub": user.email})
    refresh_token = create_refresh_token(data={"sub": user.email})
    return {
        "statusCode": 200,
        "access_token": access_token,
        "refresh_token": refresh_token,
        "token_type": "bearer",
        "username": user.username,
        "email": user.email,
    }
