from fastapi import APIRouter, Depends, HTTPException, Body
from sqlmodel import Session, select
import json
from ..core.database import get_session
from ..core.dependencies import require_role
from ..models.outpatient_center import OutpatientCenter
from ..models.user import User
from ..models.role import Role
from ..models.user_role import UserRole
from ..core.security import get_password_hash, generate_secure_password
from ..schemas.outpatient_center import (
    OutpatientCenterCreate,
    OutpatientCenterRead,
    OutpatientCenterResponse,
    OutpatientCenterUpdate,
    OutpatientCenterUserRead,
    OutpatientCenterReadOne,
)

router = APIRouter(
    prefix="/outpatient_center",
    tags=["Centro de Atención Ambulatoria"],
    responses={404: {"description": "Not found"}},
)


@router.post("/", response_model=OutpatientCenterRead)
def create_centro_ambulatorio(
    centro_data = Body(...),
    session: Session = Depends(get_session),
    current_user=Depends(require_role("admin")),
):
    if isinstance(centro_data, str):
        centro_dict = json.loads(centro_data)
    else:
        centro_dict = centro_data
    centro = OutpatientCenterCreate(**{k: v for k, v in centro_dict.items() if k in OutpatientCenterCreate.model_fields})
    existing_user = session.exec(select(User).where(User.email == centro.email)).first()
    if existing_user:
        raise HTTPException(status_code=400, detail="Email already registered")

    role = session.exec(select(Role).where(Role.name == "outpatient_center")).first()
    if not role:
        role = Role(name="outpatient_center", description="Centro ambulatorio")
        session.add(role)
        session.commit()
        session.refresh(role)

    password = get_password_hash(generate_secure_password())

    data_user = {
        "username": centro.name.lower().replace(" ", "_"),
        "email": centro.email,
        "hashed_password": password,
        "is_active": True,
        "refresh_token": None,
    }
    user = User.from_orm(data_user)
    session.add(user)
    session.commit()
    session.refresh(user)

    user_role_link = UserRole(user_id=user.id, role_id=role.id)
    session.add(user_role_link)
    session.commit()

    db_centro = OutpatientCenter(
        name=centro.name,
        address=centro.address,
        phone=centro.phone,
        email=centro.email,
        responsible=centro.responsible,
        user_id=user.id,
        capacity=int(centro.capacity),
        city=centro.city,
        currentPatients=0,
        active=centro.active,
    )

    session.add(db_centro)
    session.commit()
    session.refresh(db_centro)

    return db_centro


@router.get("/", response_model=OutpatientCenterResponse)
def list_outpatient_centers(
    session: Session = Depends(get_session), current_user=Depends(require_role("admin"))
):
    outpatient_centers = session.exec(select(OutpatientCenter)).all()
    return {
        "statusCode": 200,
        "message": "Successfully retrieved outpatient centers",
        "data": outpatient_centers,
    }


@router.get("/{centro_id}", response_model=OutpatientCenterReadOne)
def get_outpatient_center(
    centro_id: int,
    session: Session = Depends(get_session),
    current_user=Depends(require_role("admin")),
):
    centro = session.get(OutpatientCenter, centro_id)
    if not centro:
        raise HTTPException(status_code=404, detail="Centro ambulatorio no encontrado")
    return centro


@router.patch("/{centro_id}", response_model=OutpatientCenterRead)
def update_outpatient_center(
    centro_id: int,
    centro_data = Body(...),
    session: Session = Depends(get_session),
    current_user=Depends(require_role("admin")),
):
    if isinstance(centro_data, bytes):
        centro_data = centro_data.decode('utf-8')
    if isinstance(centro_data, bytes):
        centro_data = centro_data.decode('utf-8')
    if isinstance(centro_data, str):
        centro_dict = json.loads(centro_data)
    else:
        centro_dict = centro_data
    centro_update = OutpatientCenterUpdate(**{k: v for k, v in centro_dict.items() if k in OutpatientCenterUpdate.model_fields})
    centro = session.get(OutpatientCenter, centro_id)
    if not centro:
        raise HTTPException(status_code=404, detail="Centro ambulatorio no encontrado")
    for key, value in centro_update.model_dump(exclude_unset=True).items():
        setattr(centro, key, value)
    session.add(centro)
    session.commit()
    session.refresh(centro)
    return centro


@router.delete("/{centro_id}")
def delete_outpatient_center(
    centro_id: int,
    session: Session = Depends(get_session),
    current_user=Depends(require_role("admin")),
):
    centro = session.get(OutpatientCenter, centro_id)
    if not centro:
        raise HTTPException(status_code=404, detail="Centro ambulatorio no encontrado")

    user = session.exec(select(User).where(User.email == centro.email)).first()

    session.delete(centro)
    session.commit()

    if user:
        session.delete(user)
        session.commit()

    return {"msg": "Centro ambulatorio y usuario asociado eliminados"}
