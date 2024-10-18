import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { User } from '@/interfaces/User';

export const useClientStore = defineStore('client', () => {
  const clients = ref<User[]>([]);
  const errorMessages = ref<string[]>([]);

  const fetchClients = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/clients`);
      clients.value = response.data.data;
    } catch (error) {
      errorMessages.value.push('Erro ao buscar clientes.');
    }
  };

  return {
    clients,
    fetchClients,
    errorMessages,
  };
});
