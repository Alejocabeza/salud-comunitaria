from pydantic import BaseModel, EmailStr, ConfigDict, ConfigDict

class Token(BaseModel):
    statusCode: int
    username: str
    email: str
    access_token: str
    refresh_token: str
    token_type: str

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
