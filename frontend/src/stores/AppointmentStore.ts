import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useAppointmentStore = defineStore('appointment', () => {
  const name = ref('');
  const email = ref('');
  const animalName = ref('');
  const animalTypeId = ref('');
  const animalAge = ref('');
  const symptoms = ref('');
  const appointmentDate = ref('');
  const appointmentTime = ref('');
  const errorMessages = ref<string[]>([]);
  const animalAges = ref([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
  const showSuccessSubmit = ref(false);

  const createFirstAppointment = async () => {
    errorMessages.value = [];
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/appointments`, {
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
    animalAges,
    errorMessages,
    createFirstAppointment,
    showSuccessSubmit,
  };
});
