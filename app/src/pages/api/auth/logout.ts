import type { APIRoute, AstroSession } from "astro";
export const prerender = false;
export const GET: APIRoute = async (context) => {
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
    return context.redirect("/auth/login");
  }

  await session.destroy();
  locals.session = undefined;
  locals.user = null;
  return context.redirect("/auth/login");
};
