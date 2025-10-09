import type { APIRoute } from "astro";
import { PUBLIC_API_URL } from "astro:env/server";

export const prerender = false;

export const POST: APIRoute = async (context) => {
  const refreshTokenCookie = context.cookies.get("refresh_token");

  if (!refreshTokenCookie || !refreshTokenCookie.value) {
    return new Response(JSON.stringify({ message: "Refresh token no encontrado" }), {
      status: 401,
      headers: { "Content-Type": "application/json" },
    });
  }

  const refreshToken = refreshTokenCookie.value;

  try {
    const apiResponse = await fetch(`${PUBLIC_API_URL}auths/refresh`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ refresh_token: refreshToken }),
    });

    if (!apiResponse.ok) {
      const errorResult = await apiResponse.json();
      context.cookies.delete("refresh_token", { path: "/" });
      return new Response(
        JSON.stringify({
          message: errorResult.message || "Error al refrescar el token",
        }),
        { status: apiResponse.status, headers: { "Content-Type": "application/json" } },
      );
    }

    const result = await apiResponse.json();
    const { access_token, refresh_token: new_refresh_token } = result.token;

    context.cookies.set("refresh_token", new_refresh_token, {
      httpOnly: true,
      secure: import.meta.env.PROD,
      path: "/",
      maxAge: 60 * 60 * 24 * 7, // 7 días
      sameSite: "strict",
    });

    return new Response(JSON.stringify({ access_token }), {
      status: 200,
      headers: { "Content-Type": "application/json" },
    });
  } catch (error) {
    console.error("Error en el endpoint de refresh:", error);
    return new Response(
      JSON.stringify({ message: "Error interno del servidor" }),
      { status: 500, headers: { "Content-Type": "application/json" } },
    );
  }
};
