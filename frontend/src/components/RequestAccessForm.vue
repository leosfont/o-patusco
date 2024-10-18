<template>
  <form @submit.prevent="submitForm">
    <label for="email" class="block text-left mb-1 text-gray-700">Email</label>
    <input v-model="email" id="email" type="email" placeholder="Digite seu e-mail" class="border p-2 w-full mb-4" required />

    <div v-if="errorMessages.length" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
      <ul>
        <li v-for="(message, index) in errorMessages" :key="index">{{ message }}</li>
      </ul>
    </div>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Solicitar Link de Acesso</button>
  </form>

  <div v-if="successMessage" class="bg-green-100 text-green-700 p-2 mt-4 rounded">
    {{ successMessage }}
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/AuthStore';

export default {
  setup() {
    const authStore = useAuthStore();
  
    const email = ref('');
    const errorMessages = ref<string[]>([]);
    const successMessage = ref('');

    const submitForm = async () => {
      errorMessages.value = [];
      successMessage.value = '';

      try {
        await authStore.requestAccessLink(email.value);
        successMessage.value = 'Um link de acesso foi enviado para o seu e-mail.';
        email.value = '';
      } catch (errors) {
        if (typeof errors === 'string') {
          errorMessages.value.push(errors);
        } else {
          errorMessages.value = ["Erro ao enviar o link de acesso. Por favor, tente novamente."];
        }
      }
    };

    return {
      email,
      errorMessages,
      successMessage,
      submitForm,
    };
  },
};
</script>
