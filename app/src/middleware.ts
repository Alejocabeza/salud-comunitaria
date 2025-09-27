import { defineMiddleware } from "astro:middleware";

export const onRequest = defineMiddleware(async (context, next) => {
  const { url, locals } = context;

  // Define protected routes
  const protectedRoutes = ["/dashboard"];

  // Check if the current path is protected
  const isProtected = protectedRoutes.some((route) =>
    url.pathname.startsWith(route)
  );

  if (isProtected) {
    // Get user from session
    const user = context.session!.get("user");

    // Check if user is authenticated
    if (!user) {
      // Redirect to login if not authenticated
      return context.redirect("/login");
    }

    // Attach user to locals for use in pages
    (locals as any).user = user;
  }

  return next();
});
