from pydantic import BaseModel, EmailStr, ConfigDict
from typing import Optional


class OutpatientCenterUserCreate(BaseModel):
    username: str
    email: EmailStr
    password: str


class OutpatientCenterUserRead(BaseModel):
    id: int
    username: str
    email: EmailStr

    model_config = ConfigDict(from_attributes=True)


class OutpatientCenterCreate(BaseModel):
    name: str
    address: str
    phone: Optional[str] = None
    email: Optional[EmailStr] = None
    responsible: Optional[str] = None
    city: Optional[str] = None
    capacity: int
    active: bool = True


class OutpatientCenterUpdate(BaseModel):
    name: Optional[str] = None
    address: Optional[str] = None
    phone: Optional[str] = None
    email: Optional[EmailStr] = None
    responsible: Optional[str] = None
    active: Optional[bool] = None
    city: Optional[str] = None
    capacity: Optional[int] = None
    currentPatients: Optional[int] = None


class OutpatientCenterRead(BaseModel):
    id: int
    name: str
    address: str
    phone: Optional[str] = None
    email: Optional[EmailStr] = None
    responsible: Optional[str] = None
    active: bool
    user: Optional[OutpatientCenterUserRead] = None
    city: Optional[str] = None
    capacity: int
    currentPatients: Optional[int] = 0

    model_config = ConfigDict(from_attributes=True)


class OutpatientCenterReadOne(BaseModel):
    id: int
    name: str
    address: str
    phone: Optional[str] = None
    email: Optional[EmailStr] = None
    responsible: Optional[str] = None
    active: bool
    city: Optional[str] = None
    capacity: int
    currentPatients: Optional[int] = 0

    model_config = ConfigDict(from_attributes=True)


class OutpatientCenterResponse(BaseModel):
    statusCode: int
    message: str
    data: list[OutpatientCenterRead]
