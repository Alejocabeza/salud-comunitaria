from pydantic import BaseModel, EmailStr, ConfigDict, ConfigDict

class Token(BaseModel):
    data: dict
    token: dict
    statusCode: int = 200

class TokenData(BaseModel):
    email: str = None

class LoginRequest(BaseModel):
    email: str
    password: str

class ForgotPasswordRequest(BaseModel):
    email: EmailStr

class ResetPasswordRequest(BaseModel):
    token: str
    new_password: str

class RefreshTokenRequest(BaseModel):
    refresh_token: str
