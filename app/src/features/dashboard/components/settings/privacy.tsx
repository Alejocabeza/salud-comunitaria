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
import { Eye, Users, Download } from "lucide-react";

export function PrivacySettings() {
  return (
    <Card id="privacy" className="p-6 scroll-mt-24 my-6">
      <div className="space-y-6">
        <div>
          <h2 className="text-xl font-semibold text-foreground">Privacidad</h2>
          <p className="text-sm text-muted-foreground mt-1">
            Controla quién puede ver tu información y cómo se usa
          </p>
        </div>

        <div className="space-y-4 pb-6 border-b border-border">
          <div className="flex items-start gap-3">
            <div className="p-2 rounded-lg bg-primary/10">
              <Eye className="h-5 w-5 text-primary" />
            </div>
            <div className="flex-1">
              <h3 className="font-medium text-foreground">
                Visibilidad del Perfil
              </h3>
              <p className="text-sm text-muted-foreground">
                Controla quién puede ver tu perfil y tu información
              </p>
            </div>
          </div>

          <div className="ml-14 space-y-3">
            <div className="space-y-2">
              <Label htmlFor="profile-visibility">Perfil visible para</Label>
              <Select defaultValue="community">
                <SelectTrigger id="profile-visibility">
                  <SelectValue placeholder="Selecciona una opción" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="public">Todos (Público)</SelectItem>
                  <SelectItem value="community">Solo mi comunidad</SelectItem>
                  <SelectItem value="connections">
                    Solo mis conexiones
                  </SelectItem>
                  <SelectItem value="private">Solo yo (Privado)</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="flex items-center justify-between">
              <Label
                htmlFor="show-email"
                className="text-sm font-normal cursor-pointer"
              >
                Mostrar correo electrónico
              </Label>
              <Switch id="show-email" />
            </div>
            <div className="flex items-center justify-between">
              <Label
                htmlFor="show-phone"
                className="text-sm font-normal cursor-pointer"
              >
                Mostrar número de teléfono
              </Label>
              <Switch id="show-phone" />
            </div>
            <div className="flex items-center justify-between">
              <Label
                htmlFor="show-activity"
                className="text-sm font-normal cursor-pointer"
              >
                Mostrar actividad reciente
              </Label>
              <Switch id="show-activity" defaultChecked />
            </div>
          </div>
        </div>

        {/* Resto del componente igual */}
      </div>
    </Card>
  );
}
