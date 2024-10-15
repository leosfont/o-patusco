import type { AnimalType } from "./AnimalType";
import type { Doctor } from "./Doctor";

export interface Appointment {
    id: number;
    user_id: number;
    doctor_id?: number | null;
    animal_name: string;
    animal_type: AnimalType;
    doctor?: Doctor | null;
    animal_age: number;
    symptoms: string;
    appointment_date: string;
    appointment_time: 'morning' | 'afternoon';
    created_at: string;
    updated_at: string;
  }
  