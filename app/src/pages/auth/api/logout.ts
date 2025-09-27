import type { APIRoute } from "astro";

export const GET: APIRoute = async ({ redirect, session }) => {
  // Clear the session
  session!.delete("user");

  // Redirect to login
  return redirect("/login");
};
