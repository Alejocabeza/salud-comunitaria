import { Switch } from "@/shared/components/ui/switch";
import type { OutpatientCenter } from "@/shared/type/outpatient-center.type";
import type { FC } from "react";

type OutpatientCenterViewProps = {
  data?: OutpatientCenter;
};

export const OutpatientCenterView: FC<OutpatientCenterViewProps> = ({
  data,
}) => {
  return (
    <div className='flex flex-col gap-4'>
      <div className='grid grid-cols-1 md:grid-cols-2 gap-4'>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Nombre:</h3>
          <span>{data?.name}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Dirección:</h3>
          <span>{data?.address}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Ciudad:</h3>
          <span>{data?.city}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Telefono:</h3>
          <span>{data?.phone}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Email:</h3>
          <span>{data?.email}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Responsable:</h3>
          <span>{data?.responsible}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Esta Activo:</h3>
          <Switch checked={data?.active} disabled />
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Capacidad:</h3>
          <span>{data?.capacity}</span>
        </div>
        <div className='space-y-2 border rounded-lg p-4 shadow-lg'>
          <h3 className='text-md font-bold'>Pacientes Actuales:</h3>
          <span>{data?.currentPatients}</span>
        </div>
      </div>
    </div>
  );
};
