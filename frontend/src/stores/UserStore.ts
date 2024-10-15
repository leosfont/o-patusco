import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { User } from '@/interfaces/User';

export const useUserStore = defineStore('user', () => {
  const users = ref<User[]>([]);
  const errorMessages = ref<string[]>([]);

  const fetchUsers = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/users`);
      users.value = response.data.data;
    } catch (error) {
      errorMessages.value.push('Erro ao buscar clientes.');
    }
  };

  return {
    users,
    errorMessages,
    fetchUsers,
  };
});
