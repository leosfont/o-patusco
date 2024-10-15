import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { AnimalType } from '@/interfaces/AnimalType';

export const useAnimalTypeStore = defineStore('animalType', () => {
  const animalTypes = ref<AnimalType[]>([]);
  const errorMessages = ref<string[]>([]);

  const fetchAnimalTypes = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/animal-types`);
      animalTypes.value = response.data.data;
    } catch (error) {
      errorMessages.value.push('Erro ao buscar tipos de animais.');
    }
  };

  return {
    animalTypes,
    errorMessages,
    fetchAnimalTypes,
  };
});
