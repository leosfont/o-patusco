<template>
  <form v-if="!showSuccessSubmit" @submit.prevent="submitForm">
  
    <label for="name" class="block text-left mb-1 text-gray-700">Nome</label>
    <input v-model="name" id="name" type="text" placeholder="Digite seu nome" class="border p-2 w-full mb-4" />

    <label for="email" class="block text-left mb-1 text-gray-700">Email</label>
    <input v-model="email" id="email" type="email" placeholder="Digite seu email" class="border p-2 w-full mb-4" />
    
    <label for="animalName" class="block text-left mb-1 text-gray-700">Nome do Animal</label>
    <input v-model="animalName" id="animalName" type="text" placeholder="Digite o nome do seu animal" class="border p-2 w-full mb-4" />

    <label for="animalTypeId" class="block text-left mb-1 text-gray-700">Tipo de Animal</label>
    <select v-model="animalTypeId" id="animalTypeId" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione o tipo de animal</option>
      <option v-for="animalType in animalTypesStore.animalTypes" :key="animalType.id" :value="animalType.id">
        {{ animalType.name }}
      </option>
    </select>

    <label for="animalAge" class="block text-left mb-1 text-gray-700">Idade do Animal</label>
    <select v-model="animalAge" id="animalAge" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione a idade do animal</option>
      <option v-for="age in animalAges" :key="age" :value="age">{{ age }} anos</option>
    </select>

    <label for="symptoms" class="block text-left mb-1 text-gray-700">Sintomas</label>
    <textarea v-model="symptoms" id="symptoms" placeholder="Descreva os sintomas" class="border p-2 w-full mb-4"></textarea>

    <label for="appointmentDate" class="block text-left mb-1 text-gray-700">Data do Agendamento</label>
    <input v-model="appointmentDate" id="appointmentDate" type="date" class="border p-2 w-full mb-4" />

    <label for="appointmentTime" class="block text-left mb-1 text-gray-700">Turno</label>
    <select v-model="appointmentTime" id="appointmentTime" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione o turno</option>
      <option value="morning">Manhã</option>
      <option value="afternoon">Tarde</option>
    </select>
    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Solicitar Agendamento</button>
    <div v-if="errorMessages.length" class="bg-red-100 text-red-700 p-2 mt-4 rounded">
      <ul>
        <li class="text-left" v-for="(message, index) in errorMessages" :key="index">{{ message }}</li>
      </ul>
    </div>
  </form>
  <div v-if="showSuccessSubmit" class="bg-green-100 text-green-700 p-2 mt-4 rounded">
    Agendamento realizado com sucesso! Um e-mail com o link de acesso foi enviado para você.
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useAppointmentStore } from '@/stores/AppointmentStore';
import { useAnimalTypeStore } from '@/stores/AnimalTypeStore';

export default {
  setup() {
    const animalTypesStore = useAnimalTypeStore();
    const appointmentStore = useAppointmentStore();

    const name = ref('');
    const email = ref('');
    const animalName = ref('');
    const animalTypeId = ref('');
    const animalAge = ref('');
    const symptoms = ref('');
    const appointmentDate = ref('');
    const appointmentTime = ref('');
    const errorMessages = ref<string[]>([]);
    const showSuccessSubmit = ref(false);
    const animalAges = appointmentStore.animalAges;

    onMounted(() => {
      animalTypesStore.fetchAnimalTypes();
    });

    const submitForm = async () => {
      errorMessages.value = [];
      try {
        await appointmentStore.createFirstAppointment({
          name: name.value,
          email: email.value,
          animal_name: animalName.value,
          animal_type_id: animalTypeId.value,
          animal_age: animalAge.value,
          symptoms: symptoms.value,
          appointment_date: appointmentDate.value,
          appointment_time: appointmentTime.value,
        });
        showSuccessSubmit.value = true;
      } catch (error: any) {
        if (error.response && error.response.status === 422) {
          const validationErrors = error.response.data.errors;
          for (const key in validationErrors) {
            validationErrors[key].forEach((msg: string) => {
              errorMessages.value.push(msg);
            });
          }
        } else {
          errorMessages.value.push('Erro ao enviar o formulário.');
        }
      }
    };

    return {
      name,
      email,
      animalName,
      animalTypeId,
      animalAge,
      symptoms,
      appointmentDate,
      appointmentTime,
      errorMessages,
      animalAges,
      showSuccessSubmit,
      submitForm,
      animalTypesStore,
    };
  },
};
</script>
