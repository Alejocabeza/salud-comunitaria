import { PUBLIC_API_URL } from "astro:env/client";
import type { APIRoute } from "astro";

export const POST: APIRoute = async ({ request, redirect, session }) => {
  let email: string;
  let password: string;

  try {
    const formData = await request.formData();
    email = formData.get("email") as string;
    password = formData.get("password") as string;
  } catch (error) {
    try {
      const jsonData = await request.json();
      email = jsonData.email;
      password = jsonData.password;
    } catch (jsonError) {
      return new Response(
        "Invalid request format. Expected form data or JSON with email and password fields.",
        { status: 400 }
      );
    }
  }

  if (!email || !password) {
    return new Response("Missing email or password", { status: 400 });
  }

  try {
    const response = await fetch(`${PUBLIC_API_URL}auths/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({ email, password }),
    });

    const data = await response.json();

    if (response.ok) {
      // Set session data
      session!.set("user", {
        username: data.username,
        email: data.email,
        token: data.access_token,
      });

      return redirect("/dashboard");
    } else {
      return redirect(
        "/login?error=" + encodeURIComponent(data.message || "Login failed")
      );
    }
  } catch (error) {
    console.error("Error during login:", error);
    return redirect(
      "/login?error=" + encodeURIComponent("Error al iniciar sesión")
    );
  }
};
