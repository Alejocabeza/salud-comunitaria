import type { TableDataColumn } from "@/shared/components/table-data";
import { Badge } from "@/shared/components/ui/badge";
import type { OutpatientCenter } from "@/shared/type/outpatient-center.type";

export const columns: TableDataColumn<OutpatientCenter>[] = [
  {
    header: "Nombre",
    accessor: (item) => item.name,
    align: "center",
  },
  {
    header: "Dirección",
    accessor: (item) => item.address,
    align: "center",
  },
  {
    header: "Ciudad",
    accessor: (item) => item.city,
    align: "center",
  },
  {
    header: "Teléfono",
    accessor: (item) => item.phone,
    align: "center",
  },
  {
    header: "Correo Electrónico",
    accessor: (item) => item.email,
    align: "center",
  },
  {
    header: "Responsable",
    accessor: (item) => item.responsible,
    align: "right",
  },
  {
    header: "Activo",
    align: "right",
    accessor: (item) =>
      item.active ? (
        <Badge className="bg-green-500 text-white">Verdadero</Badge>
      ) : (
        <Badge className="bg-red-500 text-white">Falso</Badge>
      ),
  },
];
