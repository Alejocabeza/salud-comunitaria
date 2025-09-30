import {
  Activity,
  Bell,
  Calendar,
  FileText,
  Heart,
  Home,
  MapPin,
  Settings,
  TrendingUp,
  Users,
} from "lucide-react";

export const NAVIGATION_SIDEBAR = [
  { name: "Vista General", href: "/dashboard", icon: Activity, current: true },
  { name: "Pacientes", href: "/patients", icon: Users },
  { name: "Programas", href: "/programs", icon: Heart },
  { name: "Citas", href: "/appointments", icon: Calendar },
  { name: "Recursos", href: "/resources", icon: FileText },
  { name: "Métricas", href: "/metrics", icon: TrendingUp },
  { name: "Ubicaciones", href: "/locations", icon: MapPin },
  { name: "Alertas", href: "/alerts", icon: Bell },
  { name: "Configuración", href: "/settings", icon: Settings },
];
