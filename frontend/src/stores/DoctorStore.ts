import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { Doctor } from '@/interfaces/Doctor';

export const useDoctorStore = defineStore('doctor', () => {
  const doctors = ref<Doctor[]>([]);
  const errorMessages = ref<string[]>([]);

  const fetchDoctors = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/doctors`);
      doctors.value = response.data.data;
    } catch (error) {
      errorMessages.value.push('Erro ao buscar doutores.');
    }
  };

  return {
    doctors,
    errorMessages,
    fetchDoctors,
  };
});
