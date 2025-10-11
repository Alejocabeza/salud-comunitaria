import { setAccessToken } from "@/shared/lib/api";
import type { APIRoute, AstroSession } from "astro";
import { PUBLIC_API_URL } from "astro:env/server";

export const prerender = false;

export const POST: APIRoute = async (context) => {
  const data = await context.request.json();
  const { email, password } = data;

  const apiResponse = await fetch(`${PUBLIC_API_URL}auths/login`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ email, password }),
    credentials: "include",
  });

  if (!apiResponse.ok) {
    const errorResult = await apiResponse.json();
    return new Response(
      JSON.stringify({
        message: errorResult.message || "Error en la autenticación",
      }),
      { status: 401, headers: { "Content-Type": "application/json" } }
    );
  }

  const result = await apiResponse.json();

  const locals = context.locals as typeof context.locals & {
    session?: AstroSession;
    user?: { id: string } | null;
  };
  const sessionFromContext = (
    context as typeof context & {
      session?: AstroSession;
    }
  ).session;
  const session = locals.session ?? sessionFromContext;

  if (!session) {
    return new Response(JSON.stringify({ message: "Sesión no disponible" }), {
      status: 500,
      headers: { "Content-Type": "application/json" },
    });
  }

  if (!locals.session) {
    locals.session = session;
  }

  await session.set("user", result.data);
  await session.set("token", result.token);

  context.cookies.set("refresh_token", result.token.refresh_token, {
    httpOnly: true,
    secure: import.meta.env.PROD,
    path: "/",
    maxAge: 60 * 60 * 24 * 7,
    sameSite: "strict",
  });

  const userId = result?.user?.id;
  locals.user = userId ? { id: userId } : null;

  return new Response(
    JSON.stringify({
      message: "Autenticación exitosa",
      access_token: result.token.access_token,
    }),
    {
      status: 200,
      headers: { "Content-Type": "application/json" },
    }
  );
};
