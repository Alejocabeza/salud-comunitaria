from sqlmodel import SQLModel, Field
from typing import Optional
from datetime import datetime

class TokenBlacklist(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    token: str = Field(index=True, unique=True)
    created_at: datetime = Field(default_factory=datetime.utcnow)