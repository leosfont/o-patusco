import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { Appointment } from '@/interfaces/Appointment';

export const useUserAppointmentStore = defineStore('userAppointments', () => {
  const appointments = ref<Appointment[]>([]);
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
  });
  const currentAppointment = ref<Appointment | null>(null);
  const errorMessages = ref<string[]>([]);
  const showSuccessSubmit = ref(false);

  const filters = ref({
    animal_type_id: '',
    appointment_date: '',
  });

  const fetchAppointments = async (page = 1) => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/user/appointments`, {
        params: {
          page,
          animal_type_id: filters.value.animal_type_id || undefined,
          appointment_date: filters.value.appointment_date || undefined,
        },
      });
      appointments.value = response.data.data;
      pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        total: response.data.total,
      };
    } catch (error: any) {
      errorMessages.value.push('Erro ao carregar as consultas.');
    }
  };

  const createAppointment = async (appointmentData: any) => {
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/appointments`, appointmentData);
      showSuccessSubmit.value = true;
      await fetchAppointments(pagination.value.current_page)
    } catch (error: any) {
      errorMessages.value.push('Erro ao criar a consulta.');
    }
  };

  const updateAppointment = async (appointmentData: any) => {
    if (!currentAppointment.value) return;

    try {
      await axios.put(`${import.meta.env.VITE_API_URL}/appointments/${currentAppointment.value.id}`, appointmentData);
      showSuccessSubmit.value = true;
      await fetchAppointments(pagination.value.current_page)
    } catch (error: any) {
      errorMessages.value.push('Erro ao atualizar a consulta.');
    }
  };

  const deleteAppointment = async (id: number) => {
    try {
      await axios.delete(`${import.meta.env.VITE_API_URL}/appointments/${id}`);
      await fetchAppointments(pagination.value.current_page)
    } catch (error: any) {
      errorMessages.value.push('Erro ao excluir a consulta.');
    }
  };

  const setCurrentAppointment = (appointment: Appointment | null) => {
    currentAppointment.value = appointment;
  };

  const setFilters = (animal_type_id: string, appointment_date: string) => {
    filters.value.animal_type_id = animal_type_id;
    filters.value.appointment_date = appointment_date;
    fetchAppointments()
  };

  return {
    appointments,
    pagination,
    currentAppointment,
    errorMessages,
    showSuccessSubmit,
    filters,
    fetchAppointments,
    createAppointment,
    updateAppointment,
    deleteAppointment,
    setCurrentAppointment,
    setFilters,
  };
});
