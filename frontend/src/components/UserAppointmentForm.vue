<template>
  <form v-if="!store.showSuccessSubmit" @submit.prevent="submitForm">
    <label for="animalName" class="block text-left mb-1 text-gray-700">Nome do Animal</label>
    <input v-model="store.animalName" id="animalName" type="text" placeholder="Digite o nome do seu animal" class="border p-2 w-full mb-4" />

    <label for="animalTypeId" class="block text-left mb-1 text-gray-700">Tipo de Animal</label>
    <select v-model="store.animalTypeId" id="animalTypeId" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione o tipo de animal</option>
      <option v-for="animalType in animalTypeStore.animalTypes" :key="animalType.id" :value="animalType.id">
        {{ animalType.name }}
      </option>
    </select>

    <label for="animalAge" class="block text-left mb-1 text-gray-700">Idade do Animal</label>
    <select v-model="store.animalAge" id="animalAge" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione a idade do animal</option>
      <option v-for="age in store.animalAges" :key="age" :value="age">{{ age }} anos</option>
    </select>

    <label for="symptoms" class="block text-left mb-1 text-gray-700">Sintomas</label>
    <textarea v-model="store.symptoms" id="symptoms" placeholder="Descreva os sintomas" class="border p-2 w-full mb-4"></textarea>

    <label for="appointmentDate" class="block text-left mb-1 text-gray-700">Data do Agendamento</label>
    <input v-model="store.appointmentDate" id="appointmentDate" type="date" class="border p-2 w-full mb-4" />

    <label for="appointmentTime" class="block text-left mb-1 text-gray-700">Turno</label>
    <select v-model="store.appointmentTime" id="appointmentTime" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione o turno</option>
      <option value="morning">Manhã</option>
      <option value="afternoon">Tarde</option>
    </select>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Solicitar Agendamento</button>

    <div v-if="store.errorMessages.length" class="bg-red-100 text-red-700 p-2 mt-4 rounded">
      <ul>
        <li class="text-left" v-for="(message, index) in store.errorMessages" :key="index">{{ message }}</li>
      </ul>
    </div>
  </form>

  <div v-if="store.showSuccessSubmit" class="bg-green-100 text-green-700 p-2 mt-4 rounded">
    Agendamento realizado com sucesso!
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useUserAppointmentStore } from '@/stores/UserAppointmentStore';
import { useAnimalTypeStore } from '@/stores/AnimalTypeStore';

export default {
  setup() {
    const store = useUserAppointmentStore();
    const animalTypeStore = useAnimalTypeStore();

    onMounted(() => {
      animalTypeStore.fetchAnimalTypes();
    });

    const submitForm = async () => {
      await store.createAppointment();
    };

    return {
      store,
      animalTypeStore,
      submitForm,
    };
  },
};
</script>
