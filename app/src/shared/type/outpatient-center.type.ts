export type OutpatientCenter = {
  id: number;
  name: string;
  address: string;
  phone: string;
  email: string;
  responsible: string;
  active: boolean;
};

export type CreateOutpatientCenterDto = OutpatientCenter;
export type UpdateOutpatientCenterDto = Partial<OutpatientCenter>;
