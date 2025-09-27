"""empty message

Revision ID: 7baa139f2b38
Revises: a9b0403add79
Create Date: 2025-09-23 20:57:04.606122

"""
from typing import Sequence, Union

from alembic import op
import sqlalchemy as sa


import sqlmodel

# revision identifiers, used by Alembic.
revision: str = '7baa139f2b38'
down_revision: Union[str, None] = 'a9b0403add79'
branch_labels: Union[str, Sequence[str], None] = None
depends_on: Union[str, Sequence[str], None] = None


def upgrade() -> None:
    """Upgrade schema."""
    pass


def downgrade() -> None:
    """Downgrade schema."""
    pass
