// @ts-check
import { defineConfig, envField } from "astro/config";

import tailwindcss from "@tailwindcss/vite";

import react from "@astrojs/react";

// https://astro.build/config
export default defineConfig({
  output: "server",
  vite: {
    plugins: [tailwindcss()],
  },
  session: {
    driver: "memory",
    cookie: {
      name: "salud-comunitaria-session",
    },
  },
  env: {
    schema: {
      PUBLIC_API_URL: envField.string({ context: "server", access: "public" }),
    },
  },
  integrations: [react()],
});
