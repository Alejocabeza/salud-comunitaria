"use client";

import { Card } from "@/shared/components/ui/card";
import { Label } from "@/shared/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/shared/components/ui/select";
import { Switch } from "@/shared/components/ui/switch";
import { Palette, Monitor, Sun, Moon } from "lucide-react";

export function AppearanceSettings() {
  return (
    <Card id="appearance" className="p-6 scroll-mt-24">
      <div className="space-y-6">
        <div>
          <h2 className="text-xl font-semibold text-foreground">Apariencia</h2>
          <p className="text-sm text-muted-foreground mt-1">
            Personaliza la apariencia de la aplicación
          </p>
        </div>

        {/* Tema */}
        <div className="space-y-4 pb-6 border-b border-border">
          <div className="flex items-start gap-3">
            <div className="p-2 rounded-lg bg-primary/10">
              <Palette className="h-5 w-5 text-primary" />
            </div>
            <div className="flex-1">
              <h3 className="font-medium text-foreground">Tema</h3>
              <p className="text-sm text-muted-foreground">
                Selecciona el tema de color de la aplicación
              </p>
            </div>
          </div>

          <div className="ml-14 space-y-4">
            <div className="grid grid-cols-3 gap-3">
              <button className="flex flex-col items-center gap-2 p-4 rounded-lg border-2 border-primary bg-card">
                <Monitor className="h-6 w-6 text-primary" />
                <span className="text-sm font-medium">Sistema</span>
              </button>
              <button className="flex flex-col items-center gap-2 p-4 rounded-lg border border-border bg-card hover:border-primary transition-colors">
                <Sun className="h-6 w-6" />
                <span className="text-sm font-medium">Claro</span>
              </button>
              <button className="flex flex-col items-center gap-2 p-4 rounded-lg border border-border bg-card hover:border-primary transition-colors">
                <Moon className="h-6 w-6" />
                <span className="text-sm font-medium">Oscuro</span>
              </button>
            </div>
          </div>
        </div>

        {/* Opciones de Visualización */}
        <div className="space-y-4 pb-6 border-b border-border">
          <div>
            <h3 className="font-medium text-foreground">
              Opciones de Visualización
            </h3>
            <p className="text-sm text-muted-foreground">
              Ajusta cómo se muestra el contenido
            </p>
          </div>

          <div className="space-y-3">
            <div className="space-y-2">
              <Label htmlFor="font-size">Tamaño de fuente</Label>
              <Select defaultValue="medium">
                <SelectTrigger id="font-size">
                  <SelectValue placeholder="Selecciona un tamaño" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="small">Pequeño</SelectItem>
                  <SelectItem value="medium">Mediano</SelectItem>
                  <SelectItem value="large">Grande</SelectItem>
                  <SelectItem value="xlarge">Extra Grande</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="flex items-center justify-between">
              <Label
                htmlFor="compact-mode"
                className="text-sm font-normal cursor-pointer"
              >
                Modo compacto
              </Label>
              <Switch id="compact-mode" />
            </div>

            <div className="flex items-center justify-between">
              <Label
                htmlFor="animations"
                className="text-sm font-normal cursor-pointer"
              >
                Habilitar animaciones
              </Label>
              <Switch id="animations" defaultChecked />
            </div>

            <div className="flex items-center justify-between">
              <Label
                htmlFor="high-contrast"
                className="text-sm font-normal cursor-pointer"
              >
                Alto contraste
              </Label>
              <Switch id="high-contrast" />
            </div>
          </div>
        </div>

        {/* Accesibilidad */}
        <div className="space-y-4">
          <div>
            <h3 className="font-medium text-foreground">Accesibilidad</h3>
            <p className="text-sm text-muted-foreground">
              Opciones para mejorar la accesibilidad
            </p>
          </div>

          <div className="space-y-3">
            <div className="flex items-center justify-between">
              <Label
                htmlFor="reduce-motion"
                className="text-sm font-normal cursor-pointer"
              >
                Reducir movimiento
              </Label>
              <Switch id="reduce-motion" />
            </div>

            <div className="flex items-center justify-between">
              <Label
                htmlFor="screen-reader"
                className="text-sm font-normal cursor-pointer"
              >
                Optimizar para lectores de pantalla
              </Label>
              <Switch id="screen-reader" />
            </div>

            <div className="flex items-center justify-between">
              <Label
                htmlFor="keyboard-nav"
                className="text-sm font-normal cursor-pointer"
              >
                Navegación mejorada por teclado
              </Label>
              <Switch id="keyboard-nav" defaultChecked />
            </div>
          </div>
        </div>
      </div>
    </Card>
  );
}
