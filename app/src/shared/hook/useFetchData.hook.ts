import { toast } from "sonner";
import { useState, useCallback, useEffect } from "react";
import type { OutpatientCenter } from "../type/outpatient-center.type";
import { api } from "../lib/api";

export const useFetchData = <T>(path: string) => {
  const [data, setData] = useState<T[] | T | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [reload, setReload] = useState(false);

  const handleFindAll = useCallback(async () => {
    try {
      setIsLoading(true);
      const res = await api(path, {
        method: "GET",
      });
      const response = await res.json();
      setData(response.data);
    } catch (error) {
      console.error(error);
      toast.error("Error al obtener los datos");
    } finally {
      setIsLoading(false);
    }
  }, [path]);

  useEffect(() => {
    if (reload) {
      handleFindAll();
      setReload(false);
    }
  }, [reload, handleFindAll]);

  const handleFindOne = async (id: number) => {
    try {
      setIsLoading(true);
      const res = await api(`${path}/${id}`, {
        method: "GET",
      });
      const response = await res.json();
      setData(response.data);
    } catch (error) {
      console.error(error);
      toast.error("Error al obtener el dato");
    } finally {
      setIsLoading(false);
    }
  };

  const handleCreate = async (formData: Partial<OutpatientCenter>) => {
    try {
      setIsLoading(true);
      const res = await api(path, {
        method: "POST",
        body: JSON.stringify(formData),
      });
      const response = await res.json();
      if (response.ok) {
        toast.success("Creado con éxito");
      }
    } catch (error) {
      console.error(error);
      toast.error("Error al crear el dato");
    } finally {
      setIsLoading(false);
    }
  };

  const handleUpdate = async (
    id: number,
    formData: Partial<OutpatientCenter>
  ) => {
    try {
      setIsLoading(true);
      const res = await api(`${path}/${id}`, {
        method: "PATCH",
        body: JSON.stringify(formData),
      });
      const response = await res.json();
      if (response.ok) {
        toast.success("Actualizado con éxito");
      }
    } catch (error) {
      console.error(error);
      toast.error("Error al actualizar el dato");
    } finally {
      setIsLoading(false);
    }
  };

  const handleRemove = async (id: number) => {
    try {
      setIsLoading(true);
      const res = await api(`${path}/${id}`, {
        method: "DELETE",
      });
      const response = await res.json();
      if (response.ok) {
        toast.success("Eliminado con éxito");
      }
    } catch (error) {
      console.error(error);
      toast.error("Error al eliminar el dato");
    } finally {
      setIsLoading(false);
    }
  };

  return {
    data,
    handleFindAll,
    handleFindOne,
    handleCreate,
    handleUpdate,
    handleRemove,
    isLoading,
    setReload,
  };
};
