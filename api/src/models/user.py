from sqlmodel import SQLModel, Field, Relationship
from typing import Optional, List
from .user_role import UserRole

class User(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    username: str = Field(index=True, unique=True)
    email: str = Field(index=True, unique=True)
    hashed_password: str
    is_active: bool = Field(default=True)
    roles: List["Role"] = Relationship(back_populates="users", link_model=UserRole)
    outpatient_center: Optional["OutpatientCenter"] = Relationship(back_populates="user")
    refresh_token: Optional[str] = Field(default=None)
