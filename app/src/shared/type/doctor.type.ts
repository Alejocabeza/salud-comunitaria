import type { OutpatientCenter } from "./outpatient-center.type";

export type Doctor = {
  name: string;
  specialty: string;
  phone: string;
  email: string;
  outpatientCenterId: OutpatientCenter | null;
  user: number;
};
