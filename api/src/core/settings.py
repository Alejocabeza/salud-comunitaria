import os
from typing import List
from pydantic_settings import BaseSettings, SettingsConfigDict

class Settings(BaseSettings):
    model_config = SettingsConfigDict(
        env_file=os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), '.env'),
        env_file_encoding='utf-8',
        case_sensitive=True
    )

    DATABASE_URL: str
    APP_TITLE: str = "Salud Comunitaria API"
    APP_DESCRIPTION: str = "Aplicación de gestión de salud comunitaria"
    APP_VERSION: str = "1.0.0"

    SECRET_KEY: str = "default-secret-key"
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60

    # SMTP
    MAIL_USERNAME: str = ""
    MAIL_PASSWORD: str = ""
    MAIL_PORT: int = 587
    MAIL_FROM: str = ""
    MAIL_FROM_NAME: str = "Salud Comunitaria API"
    MAIL_SERVER: str = ""
    MAIL_STARTTLS: bool = True
    MAIL_SSL_TLS: bool = False
    USE_CREDENTIALS: bool = True
    VALIDATE_CERTS: bool = True

    # CORS
    BACKEND_CORS_ORIGINS: List[str] = ["*"]
    BACKEND_CORS_ALLOW_CREDENTIALS: bool = False

    # Allowed Files
    ALLOWED_EXTENSIONS: List[str] = ["pdf", "jpg", "png", "docx", "txt", "jpeg"]
    MAX_FILE_SIZE_MB: int = 5

    @property
    def MAX_FILE_SIZE_BYTES(self) -> int:
        return self.MAX_FILE_SIZE_MB * 1024 * 1024

settings = Settings()