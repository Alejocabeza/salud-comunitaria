import os
import json
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
    # Keep the raw env value as a string to avoid pydantic's env-source JSON decoding
    BACKEND_CORS_ORIGINS: str = ""
    BACKEND_CORS_ALLOW_CREDENTIALS: bool = False

    @property
    def BACKEND_CORS_ORIGINS_LIST(self) -> List[str]:
        """Return a list of origins parsed from the env value.

        Behavior:
        - empty string or unset -> ['*']
        - JSON array string -> parsed
        - comma-separated string -> split
        """
        v = self.BACKEND_CORS_ORIGINS
        if isinstance(v, (list, tuple)):
            return list(v)

        if not v:
            return ["*"]

        s = v.strip()
        if not s:
            return ["*"]

        if s.startswith("[") or s.startswith("{"):
            try:
                parsed = json.loads(s)
                if isinstance(parsed, list):
                    return [str(x) for x in parsed]
            except Exception:
                pass

        return [item.strip() for item in s.split(",") if item.strip()]

    # Allowed Files
    ALLOWED_EXTENSIONS: List[str] = ["pdf", "jpg", "png", "docx", "txt", "jpeg"]
    MAX_FILE_SIZE_MB: int = 5

    @property
    def MAX_FILE_SIZE_BYTES(self) -> int:
        return self.MAX_FILE_SIZE_MB * 1024 * 1024

settings = Settings()