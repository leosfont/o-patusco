import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';
import type { AppointmentFilter } from '@/interfaces/AppointmentFilter';

export const useAppointmentStore = defineStore('appointment', () => {
  const appointments = ref<any[]>([]);
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
  });

  const animalAges = ref(Array.from({ length: 20 }, (_, i) => i + 1));

  const createFirstAppointment = async (appointmentData: any) => {
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/create-first-appointment`, {
        name: appointmentData.name,
        email: appointmentData.email,
        animal_name: appointmentData.animal_name,
        animal_type_id: appointmentData.animal_type_id,
        animal_age: appointmentData.animal_age,
        symptoms: appointmentData.symptoms,
        appointment_date: appointmentData.appointment_date,
        appointment_time: appointmentData.appointment_time,
      });
    } catch (error: any) {
      throw error;
    }
  };

  const createAppointment = async (appointmentData: any) => {
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/appointments`, appointmentData);
      await fetchAppointments(pagination.value.current_page);
    } catch (error: any) {
      throw error;
    }
  };


  const fetchAppointments = async (page = 1, filters: AppointmentFilter = {}) => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/appointments`, {
        params: {
          page,
          ...filters,
        },
      });
      appointments.value = response.data.data;
      pagination.value = {
        current_page: response.data.meta.current_page,
        last_page: response.data.meta.last_page,
        total: response.data.meta.total,
      };
    } catch (error) {
      throw error;
    }
  };

  const deleteAppointment = async (id: string) => {
    try {
      await axios.delete(`${import.meta.env.VITE_API_URL}/appointments/${id}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      fetchAppointments(pagination.value.current_page);
    } catch (error) {
      throw error;
    }
  };

  const updateAppointment = async (id: string, appointmentData: any) => {
    try {
      await axios.put(`${import.meta.env.VITE_API_URL}/appointments/${id}`, {
        doctor_id: appointmentData.doctor_id,
        animal_name: appointmentData.animal_name, 
        animal_age: appointmentData.animal_age,
        animal_type_id: appointmentData.animal_type_id,
        appointment_date: appointmentData.appointment_date,
        appointment_time: appointmentData.appointment_time,
        symptoms: appointmentData.symptoms,
      });
      fetchAppointments(pagination.value.current_page);
    } catch (error) {
      throw error;
    }
  };

  return {
    appointments,
    pagination,
    animalAges,
    createAppointment,
    fetchAppointments,
    deleteAppointment,
    updateAppointment,
    createFirstAppointment,
  };
});
