<template>
      <form @submit.prevent="submitForm">
        <label for="email" class="block text-left mb-1 text-gray-700">Email</label>
        <input v-model="store.email" id="email" type="email" placeholder="Digite seu e-mail" class="border p-2 w-full mb-4" required />
        <div v-if="store.errorMessages.length" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
            <ul>
            <li v-for="(message, index) in store.errorMessages" :key="index">{{ message }}</li>
            </ul>
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Solicitar Link de Acesso</button>
      </form>
      <div v-if="store.successMessage" class="bg-green-100 text-green-700 p-2 mt-4 rounded">
        {{ store.successMessage }}
      </div>
  </template>
  
  <script lang="ts">
  import { useAuthStore } from '@/stores/AuthStore';
  
  export default {
    setup() {
      const store = useAuthStore();
  
      const submitForm = async () => {
        await store.requestAccessLink();
      };
  
      return {
        store,
        submitForm,
      };
    },
  };
  </script>
  