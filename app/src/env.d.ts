/// <reference path="../.astro/types.d.ts" />

import type { AstroSession } from "astro";

declare namespace App {
  interface Locals {
    user?: Record<string, any> | null;
    session?: AstroSession;
  }
}
