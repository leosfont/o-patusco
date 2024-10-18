import { defineStore } from 'pinia';
import axios from 'axios';
import router from '@/router';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const token = ref('');
  const isAuthenticated = ref(false);

  const requestAccessLink = async (email: string) => {
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/request-access-link`, { email });
    } catch (error: any) {
      const errorMessages: string[] = [];
      if (error.response && error.response.status === 422) {
        const validationErrors = error.response.data.errors;
        for (const key in validationErrors) {
          validationErrors[key].forEach((msg: string) => {
            errorMessages.push(msg);
          });
        }
      } else {
        errorMessages.push('Erro ao enviar a solicitação de acesso.');
      }
      throw errorMessages;
    }
  };

  const loginWithToken = async (accessToken: string) => {
    try {
      token.value = accessToken;
      isAuthenticated.value = true;

      localStorage.setItem('token', token.value);

      router.push('/appointments');
    } catch (error: any) {
      throw new Error('Token inválido ou expirado.');
    }
  };

  const logout = () => {
    token.value = '';
    isAuthenticated.value = false;
    localStorage.removeItem('token');
  };

  return {
    token,
    isAuthenticated,
    requestAccessLink,
    loginWithToken,
    logout,
  };
});
