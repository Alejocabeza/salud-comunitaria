import { TableData } from "@/shared/components/table-data";
import { useFetchData } from "@/shared/hook/useFetchData.hook";
import type { Doctor } from "@/shared/type/doctor.type";
import { useEffect } from "react";
import { columns } from "../components/doctor/column";

export const DoctorsPage = () => {
  const {
    data,
    isLoading,
    handleFindAll,
    handleCreate,
    handleUpdate,
    handleRemove,
    setReload,
  } = useFetchData<Doctor>("doctors");

  useEffect(() => {
    handleFindAll();
  }, [handleFindAll]);

  if (isLoading && !data) {
    return <p>Cargando datos...</p>;
  }

  const onCreate = async (formData: Partial<Doctor>) => {
    await handleCreate(formData as Doctor);
    setReload(true);
  };

  const onEdit = async (id: number, formData: Partial<Doctor>) => {
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
      data={(data as Doctor[]) || []}
      title="Medicos"
      creacteComponent={({ onSubmit }) => <div>Crea Medico</div>}
      editComponent={({ initialData, onSubmit }) => <div>Editar Medico</div>}
      viewComponent={({ initialData }) => <div>Ver Medico</div>}
      onCreate={onCreate}
      onedit={onEdit}
      onDelete={onDelete}
      searchableColumns={(item: Doctor) => [
        item.name,
        item.email,
        item.phone,
        item.specialty,
      ]}
    />
  );
};
