# migrations/env.py
import os
import sys
from logging.config import fileConfig
from dotenv import load_dotenv

from sqlalchemy import engine_from_config
from sqlalchemy import pool
from sqlalchemy.engine import Connection
from alembic import context

# This is necessary for Alembic to import modules from your application
# Add the root directory of your project (where `app/`) is to sys.path
sys.path.insert(0, os.path.realpath(os.path.join(os.path.dirname(__file__), '..')))

# Load .env file
dotenv_path = os.path.join(os.path.dirname(os.path.dirname(__file__)), '.env')
if os.path.exists(dotenv_path):
    load_dotenv(dotenv_path=dotenv_path)
else:
    print(f"Warning: .env file not found at {dotenv_path}")

# Import your settings and models
from src.core.settings import settings # To get DATABASE_URL
from sqlmodel import SQLModel  # Importa SQLModel desde el paquete correcto
from src.models.outpatient_center import OutpatientCenter
from src.models.user import User
from src.models.role import Role
from src.models.permission import Permission
from src.models.user_role import UserRole
from src.models.role_permission import RolePermission
from src.models.doctor import Doctor
from src.models.patient import Patient
from src.models.medical_resource import MedicalResource
from src.models.medication_request import MedicationRequest
from src.models.external_document import ExternalDocument
from src.models.token_blacklist import TokenBlacklist

# Alembic config, read from alembic.ini
config = context.config

# Interpret the config file for Python logging.
if config.config_file_name is not None:
    fileConfig(config.config_file_name)

# Set the database URL from your settings
config.set_main_option('sqlalchemy.url', settings.DATABASE_URL)

# target_metadata for 'autogenerate' operations
target_metadata = SQLModel.metadata

def run_migrations_offline() -> None:
    """Run migrations in 'offline' mode."""
    url = config.get_main_option("sqlalchemy.url")
    context.configure(
        url=url,
        target_metadata=target_metadata,
        literal_binds=True,
        dialect_opts={"paramstyle": "named"},
        render_as_batch=True # IMPORTANT for SQLite
    )

    with context.begin_transaction():
        context.run_migrations()

def run_migrations_online() -> None:
    """Run migrations in 'online' mode."""
    connectable = engine_from_config(
        config.get_section(config.config_ini_section, {}),
        prefix="sqlalchemy.",
        poolclass=pool.NullPool,
    )

    with connectable.connect() as connection:
        context.configure(
            connection=connection,
            target_metadata=target_metadata,
            render_as_batch=True # IMPORTANT for SQLite
        )

        with context.begin_transaction():
            context.run_migrations()

if context.is_offline_mode():
    run_migrations_offline()
else:
    run_migrations_online()
