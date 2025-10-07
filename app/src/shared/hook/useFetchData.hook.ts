import { toast } from "sonner";
import { useState, useCallback } from "react";
import type {
  CreateOutpatientCenterDto,
  UpdateOutpatientCenterDto,
} from "../type/outpatient-center.type";

export const useFetchData = <T>(token: string, path: string) => {
  const [data, setData] = useState<T[] | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const url = import.meta.env.PUBLIC_API_URL + path;

  const handleFindAll = useCallback(async () => {
    try {
      setIsLoading(true);
      const res = await fetch(url, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      });
      const response = await res.json();
      setData(response.data);
    } catch (error) {
      console.error(error);
      toast.error("Error al obtener los datos");
    } finally {
      setIsLoading(false);
    }
  }, [token, url]);

  const handleFindOne = async (id: number) => {
    try {
    } catch (error) {
      console.error(error);
      toast.error("Error al obtener el dato");
    }
  };

  const handleCreate = async (formData: CreateOutpatientCenterDto) => {
    try {
    } catch (error) {
      console.error(error);
      toast.error("Error al crear el dato");
    }
  };

  const handleUpdate = async (id: number, formData: UpdateOutpatientCenterDto) => {
    try {
    } catch (error) {
      console.error(error);
      toast.error("Error al actualizar el dato");
    }
  };

  const handleRemove = async (id: number) => {
    try {
    } catch (error) {
      console.error(error);
      toast.error("Error al eliminar el dato");
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
  };
};
