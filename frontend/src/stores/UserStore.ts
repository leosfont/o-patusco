import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { User } from '@/interfaces/User';

export const useUserStore = defineStore('user', () => {
  const user = ref<User | null>(null);
  const userRoles = ref<string[]>([]);
  const users = ref<User[]>([]);
  const errorMessages = ref<string[]>([]);

  const fetchUser = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`);
      user.value = response.data.data;
      userRoles.value = response.data.data.roles;
    } catch (error) {
      errorMessages.value.push('Erro ao buscar cliente.');
    }
  };

  return {
    user,
    userRoles,
    errorMessages,
    fetchUser,
  };
});
