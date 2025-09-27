import pytest
from src.models.user import User
from src.core.security import get_password_hash, create_reset_password_token

class TestAuthRoutes:

    def test_login_success(self, client, session):
        user = User(
            username="loginuser",
            email="loginuser@example.com",
            hashed_password=get_password_hash("loginpass")
        )
        session.add(user)
        session.commit()

        response = client.post("/api/v1/auths/login", json={
            "email": "loginuser@example.com",
            "password": "loginpass"
        })
        assert response.status_code == 200
        data = response.json()
        assert "access_token" in data
        assert "refresh_token" in data
        assert data["token_type"] == "bearer"

    def test_login_wrong_password(self, client, session):
        user = User(
            username="wrongpass",
            email="wrong@example.com",
            hashed_password=get_password_hash("correctpass")
        )
        session.add(user)
        session.commit()

        response = client.post("/api/v1/auths/login", json={
            "email": "wrong@example.com",
            "password": "incorrect"
        })
        assert response.status_code == 401
        assert "Incorrect email or password" in response.json()["detail"]

    def test_login_nonexistent_user(self, client, session):
        response = client.post("/api/v1/auths/login", json={
            "email": "noexiste@example.com",
            "password": "any"
        })
        assert response.status_code == 401
        assert "Incorrect email or password" in response.json()["detail"]

    def test_forgot_password_existing_email(self, client, session):
        user = User(
            username="forgotuser",
            email="forgot@example.com",
            hashed_password=get_password_hash("oldpass")
        )
        session.add(user)
        session.commit()

        response = client.post("/api/v1/auths/forgot-password", json={
            "email": "forgot@example.com"
        })
        assert response.status_code == 200
        assert "recibirás instrucciones" in response.json()["message"]

    def test_forgot_password_nonexistent_email(self, client, session):
        response = client.post("/api/v1/auths/forgot-password", json={
            "email": "noexiste@example.com"
        })
        assert response.status_code == 200
        assert "recibirás instrucciones" in response.json()["message"]

    def test_reset_password_valid_token(self, client, session):
        user = User(
            username="resetuser",
            email="reset@example.com",
            hashed_password=get_password_hash("oldpass")
        )
        session.add(user)
        session.commit()

        token = create_reset_password_token("reset@example.com")

        response = client.post("/api/v1/auths/reset-password", json={
            "token": token,
            "new_password": "newpass123"
        })
        assert response.status_code == 200
        assert "restablecida correctamente" in response.json()["message"]

        # Verifica login con la nueva contraseña
        login_response = client.post("/api/v1/auths/login", json={
            "email": "reset@example.com",
            "password": "newpass123"
        })
        assert login_response.status_code == 200

    def test_reset_password_invalid_token(self, client, session):
        response = client.post("/api/v1/auths/reset-password", json={
            "token": "invalid_token",
            "new_password": "newpass123"
        })
        assert response.status_code == 400
        assert "Token inválido o expirado" in response.json()["detail"]

    def test_reset_password_nonexistent_user(self, client, session):
        # Token válido para un email que no existe
        token = create_reset_password_token("noexiste@example.com")
        response = client.post("/api/v1/auths/reset-password", json={
            "token": token,
            "new_password": "newpass123"
        })
        assert response.status_code == 404
        assert "Usuario no encontrado" in response.json()["detail"]

    def test_refresh_token_success(self, client, session):
        user = User(
            username="refreshuser",
            email="refresh@example.com",
            hashed_password=get_password_hash("refreshpass")
        )
        session.add(user)
        session.commit()

        # Login to get tokens
        login_response = client.post("/api/v1/auths/login", json={
            "email": "refresh@example.com",
            "password": "refreshpass"
        })
        assert login_response.status_code == 200
        login_data = login_response.json()
        refresh_token = login_data["refresh_token"]

        # Refresh
        response = client.post("/api/v1/auths/refresh", json={
            "refresh_token": refresh_token
        })
        assert response.status_code == 200
        data = response.json()
        assert "access_token" in data
        assert "refresh_token" in data

    def test_refresh_token_invalid(self, client, session):
        response = client.post("/api/v1/auths/refresh", json={
            "refresh_token": "invalid_token"
        })
        assert response.status_code == 401
        assert "Invalid refresh token" in response.json()["detail"]

    def test_logout_success(self, client, session):
        user = User(
            username="logoutuser",
            email="logout@example.com",
            hashed_password=get_password_hash("logoutpass")
        )
        session.add(user)
        session.commit()

        # Login
        login_response = client.post("/api/v1/auths/login", json={
            "email": "logout@example.com",
            "password": "logoutpass"
        })
        assert login_response.status_code == 200
        login_data = login_response.json()
        refresh_token = login_data["refresh_token"]

        # Logout
        response = client.post("/api/v1/auths/logout", json={
            "refresh_token": refresh_token
        })
        assert response.status_code == 200
        assert "Logged out successfully" in response.json()["message"]

        # Try to refresh with logged out token
        refresh_response = client.post("/api/v1/auths/refresh", json={
            "refresh_token": refresh_token
        })
        assert refresh_response.status_code == 401
        assert "Refresh token revoked" in refresh_response.json()["detail"]