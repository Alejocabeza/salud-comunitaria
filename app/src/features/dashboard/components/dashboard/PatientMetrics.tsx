import React from "react";
import { Card } from "@/shared/components/ui/card";
import {
  ResponsiveContainer,
  LineChart,
  CartesianGrid,
  XAxis,
  YAxis,
  Line,
  AreaChart,
  Area,
} from "recharts";

const patientData = [
  { name: "Ene", pacientes: 1100, nuevos: 45 },
  { name: "Feb", pacientes: 1150, nuevos: 52 },
  { name: "Mar", pacientes: 1200, nuevos: 48 },
  { name: "Abr", pacientes: 1180, nuevos: 61 },
  { name: "May", pacientes: 1220, nuevos: 55 },
  { name: "Jun", pacientes: 1247, nuevos: 58 },
];

const appointmentData = [
  { time: "08:00", citas: 12 },
  { time: "10:00", citas: 18 },
  { time: "12:00", citas: 15 },
  { time: "14:00", citas: 22 },
  { time: "16:00", citas: 19 },
  { time: "18:00", citas: 8 },
];

export default function PatientMetrics() {
  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <Card className="health-chart-container">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold">Crecimiento de Pacientes</h3>
          <div className="flex items-center gap-4 text-sm">
            <div className="flex items-center gap-2">
              <div className="w-3 h-3 bg-primary rounded-full"></div>
              <span>Total</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-3 h-3 bg-[color:var(--health-success)] rounded-full"></div>
              <span>Nuevos</span>
            </div>
          </div>
        </div>
        <ResponsiveContainer width="100%" height={300}>
          <LineChart data={patientData}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--border)" />
            <XAxis dataKey="name" stroke="var(--muted-foreground)" />
            <YAxis stroke="var(--muted-foreground)" />
            <Line
              type="monotone"
              dataKey="pacientes"
              stroke="var(--primary)"
              strokeWidth={2}
              dot={{ fill: "var(--primary)", strokeWidth: 2 }}
            />
            <Line
              type="monotone"
              dataKey="nuevos"
              stroke="var(--health-success)"
              strokeWidth={2}
              dot={{ fill: "var(--health-success)", strokeWidth: 2 }}
            />
          </LineChart>
        </ResponsiveContainer>
      </Card>

      <Card className="health-chart-container">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold">Citas por Hora</h3>
          <span className="text-sm text-muted-foreground">Hoy</span>
        </div>
        <ResponsiveContainer width="100%" height={300}>
          <AreaChart data={appointmentData}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--border)" />
            <XAxis dataKey="time" stroke="var(--muted-foreground)" />
            <YAxis stroke="var(--muted-foreground)" />
            <Area
              type="monotone"
              dataKey="citas"
              stroke="var(--health-info)"
              fill="var(--health-info)"
              fillOpacity={0.3}
            />
          </AreaChart>
        </ResponsiveContainer>
      </Card>
    </div>
  );
}
