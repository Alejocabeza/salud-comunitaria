import type { TableDataColumn } from "@/shared/components/table-data";
import type { Doctor } from "@/shared/type/doctor.type";

export const columns: TableDataColumn<Doctor>[] = [
  {
    header: "Nombre",
    accessor: (item) => item.name,
    align: "center",
  },
  {
    header: "email",
    accessor: (item) => item.email,
    align: "center",
  },
  {
    header: "Teléfono",
    accessor: (item) => item.phone,
    align: "center",
  },
  {
    header: "Especialidad",
    accessor: (item) => item.specialty,
    align: "center",
  },
  {
    header: "Centro Ambulatorio",
    accessor: (item) => item.outpatientCenterId?.name || "N/A",
    align: "center",
  }
];
