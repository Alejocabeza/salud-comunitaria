import {
  Activity,
  Hospital,
} from "lucide-react";

export const NAVIGATION_SIDEBAR = [
  { name: "Vista General", href: "/dashboard", icon: Activity, current: true },
  { name: "Centros Ambulatorios", href: "/outpatient-centers", icon: Hospital },
];
