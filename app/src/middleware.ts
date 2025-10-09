import type { AstroSession } from "astro";
import { defineMiddleware } from "astro:middleware";

const protectedRoutes = [
  "/dashboard",
  "/profile",
  "/settings",
  "/outpatient-centers",
];

export const onRequest = defineMiddleware(async (context, next) => {
  const currentPath = new URL(context.request.url).pathname;

  const locals = context.locals as typeof context.locals & {
    session?: AstroSession;
    user?: { id: string } | null;
    token?: string | null;
  };

  const sessionFromContext = (
    context as typeof context & {
      session?: AstroSession;
    }
  ).session;

  const session = locals.session ?? sessionFromContext;

  if (session && !locals.session) {
    locals.session = session;
  }

  const user = session ? await session.get("user") : null;
  const token = session ? await session.get("token") : null;
  const userId = user?.id ?? null;

  if (protectedRoutes.includes(currentPath) && !userId) {
    return context.redirect("/auth/login");
  }

  if (currentPath === "/auth/login" && userId) {
    return context.redirect("/dashboard");
  }

  locals.user = userId ? user : null;
  locals.token = token;
  return next();
});
