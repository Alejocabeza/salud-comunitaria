import { TableData } from "@/shared/components/cruds/table-data";
import { type TableDataColumn } from "@/shared/components/cruds/table-data";
import { Badge } from "@/shared/components/ui/badge";
import { useFetchData } from "@/shared/hook/useFetchData.hook";
import type {
  CreateOutpatientCenterDto,
  OutpatientCenter,
  UpdateOutpatientCenterDto,
} from "@/shared/type/outpatient-center.type";
import { useEffect, type FC } from "react";
import { OutpatientCenterForm } from "../components/outpatient-center/form/outpatientCenterForm";

type OutpatientCenterProps = {
  token: string;
};

export const OutpatientCenterPage: FC<OutpatientCenterProps> = ({ token }) => {
  const {
    data,
    isLoading,
    handleFindAll,
    handleFindOne,
    handleCreate,
    handleUpdate,
    handleRemove,
  } = useFetchData(token, "outpatient_center");

  useEffect(() => {
    handleFindAll();
  }, [handleFindAll]);

  const columns: TableDataColumn<OutpatientCenter>[] = [
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
      align: "center",
    },
    {
      header: "Activo",
      align: "center",
      accessor: (item) =>
        item.active ? (
          <Badge className="bg-green-500 text-white">Verdadero</Badge>
        ) : (
          <Badge className="bg-red-500 text-white">Falso</Badge>
        ),
    },
  ];

  if (isLoading && !data) {
    return <p>Cargando datos...</p>;
  }

  return (
    <TableData
      columns={columns}
      data={data || []}
      title="Centros de Atención Ambulatoria"
      idAccessor={(item) => item.id.toString()}
      createComponent={() => (
        <OutpatientCenterForm
          handleCreate={handleCreate}
          isLoading={isLoading}
        />
      )}
      editComponent={() => null}
    />
  );
};
