import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useLoaderStore = defineStore('loading', () => {
  const isLoading = ref(false);

  const setLoading = (status: boolean) => {
    isLoading.value = status;
  };

  return {
    isLoading,
    setLoading,
  };
});
