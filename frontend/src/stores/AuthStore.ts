import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  const email = ref('');
  const errorMessages = ref<string[]>([]);
  const successMessage = ref('');
  const token = ref('');
  const isAuthenticated = ref(false);

  const requestAccessLink = async () => {
    errorMessages.value = [];
    successMessage.value = '';

    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/request-access-link`, {
        email: email.value,
      });

      successMessage.value = 'Um link de acesso foi enviado para o seu e-mail.';
      email.value = '';
    } catch (error: any) {
      if (error.response && error.response.status === 422) {
        const validationErrors = error.response.data.errors;
        for (const key in validationErrors) {
          validationErrors[key].forEach((msg: string) => {
            errorMessages.value.push(msg);
          });
        }
      } else {
        errorMessages.value.push('Erro ao enviar a solicitação de acesso.');
      }
    }
  };

  const loginWithToken = async (accessToken: string) => {
    try {
      token.value = accessToken;
      isAuthenticated.value = true;

      localStorage.setItem('token', token.value);

      router.push('/user/appointments');
    } catch (error: any) {
      errorMessages.value.push('Token inválido ou expirado.');
    }
  };

  const logout = () => {
    token.value = '';
    isAuthenticated.value = false;
    localStorage.removeItem('token');
  };

  return {
    email,
    errorMessages,
    successMessage,
    token,
    isAuthenticated,
    requestAccessLink,
    loginWithToken,
    logout,
  };
});
