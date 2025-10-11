import { TableData } from "@/shared/components/table-data";
import { useFetchData } from "@/shared/hook/useFetchData.hook";
import { useEffect, type FC } from "react";
import { columns } from "../components/outpatient-center/column";
import type { OutpatientCenter } from "@/shared/type/outpatient-center.type";
import { OutpatientCenterForm } from "../components/outpatient-center/outpatientCenterForm";
import { OutpatientCenterView } from "../components/outpatient-center/outpatientCenterView";
import { OutpatientCenterCards } from "../components/outpatient-center/outpatientCenterCards";
import { Check, Hospital, UserPlus, Users } from "lucide-react";

export const OutpatientCenterPage = () => {
  const {
    data,
    isLoading,
    handleFindAll,
    handleCreate,
    handleUpdate,
    handleRemove,
    setReload,
  } = useFetchData<OutpatientCenter>("outpatient_center");

  useEffect(() => {
    handleFindAll();
  }, [handleFindAll]);

  if (isLoading && !data) {
    return <p>Cargando datos...</p>;
  }

  const onCreate = async (formData: Partial<OutpatientCenter>) => {
    await handleCreate(formData as OutpatientCenter);
    setReload(true);
  };

  const onEdit = async (id: number, formData: Partial<OutpatientCenter>) => {
    await handleUpdate(id, formData);
    setReload(true);
  };

  const onDelete = async (id: number) => {
    await handleRemove(id);
    setReload(true);
  };

  return (
    <TableData
      columns={columns}
      data={(data as OutpatientCenter[]) || []}
      title="Centros de Atención Ambulatoria"
      idAccessor={(item) => item.id.toString()}
      createComponent={({ onSubmit }) => (
        <OutpatientCenterForm isLoading={isLoading} onSubmit={onSubmit} />
      )}
      editComponent={({ initialData, onSubmit }) => (
        <OutpatientCenterForm
          isLoading={isLoading}
          initialData={initialData}
          onSubmit={onSubmit}
        />
      )}
      viewComponent={({ initialData }) => (
        <OutpatientCenterView data={initialData} />
      )}
      onCreate={onCreate}
      onEdit={onEdit}
      onDelete={onDelete}
      searchAccessor={(item: OutpatientCenter): string[] => [
        item.name,
        item.email,
        item.responsible,
      ]}
      infoCards={
        <OutpatientCenterCards
          data={[
            {
              title: "Total de Centros Ambulatorios",
              data: 3,
              icon: Hospital,
            },
            {
              title: "Centros Activos",
              data: 2,
              icon: Check,
            },
            {
              title: "Capacidad total",
              data: 330,
              icon: Users,
            },
            {
              title: "Pacientes Actuales",
              data: 250,
              icon: UserPlus,
            },
          ]}
        />
      }
    />
  );
};
