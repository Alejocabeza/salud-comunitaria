import { Button } from "@/shared/components/ui/button";
import { Input } from "@/shared/components/ui/input";
import { Label } from "@/shared/components/ui/label";
import { Switch } from "@/shared/components/ui/switch";
import type { CreateOutpatientCenterDto } from "@/shared/type/outpatient-center.type";
import { useState, type FC } from "react";

type OutpatientCenterFormProps = {
  handleCreate?: () => Promise<void>;
  isLoading?: boolean;
};

export const OutpatientCenterForm: FC<OutpatientCenterFormProps> = ({
  handleCreate,
  isLoading,
}) => {
  const [formData, setFormData] = useState<CreateOutpatientCenterDto>({});

  const submitHandler = async (e: React.FormEvent) => {
    e.preventDefault();
    await handleCreate(formData);
  };

  return (
    <form className="flex flex-col gap-4" onSubmit={submitHandler}>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="name">Nombre</Label>
          <Input
            id="name"
            type="text"
            value={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            placeholder="Ambulatorio Central"
            required
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor="responsible">Director</Label>
          <Input
            id="responsible"
            value={formData.responsible}
            type="text"
            onChange={(e) =>
              setFormData({ ...formData, responsible: e.target.value })
            }
            placeholder="Dr. Juan Pérez"
            required
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor="address">Direccion</Label>
          <Input
            id="address"
            value={formData.address}
            type="text"
            onChange={(e) =>
              setFormData({ ...formData, address: e.target.value })
            }
            placeholder="Av. Siempre Viva 742"
            required
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor="phone">Telefono</Label>
          <Input
            id="phone"
            value={formData.phone}
            onChange={(e) =>
              setFormData({ ...formData, phone: e.target.value })
            }
            type="tel"
            placeholder="555-1234"
            required
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor="email">Correo Electronico</Label>
          <Input
            id="email"
            value={formData.email}
            onChange={(e) =>
              setFormData({ ...formData, email: e.target.value })
            }
            type="email"
            placeholder="ambulatoriocentral@gmail.com"
            required
          />
        </div>
        <div className="space-y-2 col-span-full">
          <Label htmlFor="active">Activo</Label>
          <Switch
            onCheckedChange={(checked) =>
              setFormData({ ...formData, active: checked })
            }
            id="active"
            value={formData.active ? "true" : "false"}
          />
        </div>
        <div className="space-y-2 col-span-full flex justify-end gap-2">
          <Button type="reset" className="cursor-pointer hover:bg-red-800">
            Cancelar
          </Button>
          <Button type="submit" className="cursor-pointer hover:bg-green-800">
            {isLoading ? "Guardando..." : "Guardar"}
          </Button>
        </div>
      </div>
    </form>
  );
};
