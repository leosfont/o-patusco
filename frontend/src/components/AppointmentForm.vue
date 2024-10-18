<template>
  <form @submit.prevent="editAppointment ? updateSubmit() : createSubmit()">
    <label for="animalName" class="block text-left mb-1 text-gray-700">Nome do Animal</label>
    <input v-model="animalName" id="animalName" type="text" placeholder="Digite o nome do seu animal" class="border p-2 w-full mb-4" />

    <label for="animalTypeId" class="block text-left mb-1 text-gray-700">Tipo de Animal</label>
    <select v-model="animalTypeId" id="animalTypeId" class="border p-2 w-full mb-4">
      <option disabled value="">Selecione o tipo de animal</option>
      <option v-for="animalType in animalTypeStore.animalTypes" :key="animalType.id" :value="animalType.id">
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

    <div v-if="userRoles?.includes('receptionist')" class="mb-4">
      <label for="doctorId" class="block text-left mb-1 text-gray-700">Médico</label>
      <select v-model="doctorId" id="doctorId" class="border p-2 w-full mb-4">
        <option disabled value="">Selecione o médico</option>
        <option v-for="doctor in doctorStore.doctors" :key="doctor.id" :value="doctor.id">
          {{ doctor.name }}
        </option>
      </select>

      <div v-if="editAppointment">
        <label for="userId" class="block text-left mb-1 text-gray-700">Cliente</label>
        <select v-model="userId" id="userId" class="border p-2 w-full mb-4">
          <option disabled value="">Selecione o paciente</option>
          <option v-for="client in clientStore.clients" :key="client.id" :value="client.id">
            {{ client.name }}
          </option>
        </select>
      </div>
    </div>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">{{ editAppointment ? 'Atualizar' : 'Solicitar' }} Agendamento</button>

    <div v-if="errorMessages.length" class="bg-red-100 text-red-700 p-2 mt-4 rounded">
      <ul>
        <li class="text-left" v-for="(message, index) in errorMessages" :key="index">{{ message }}</li>
      </ul>
    </div>
  </form>

  <div v-if="successMessage" class="bg-green-100 text-green-700 p-2 mt-4 rounded">
    {{ successMessage }}
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useAppointmentStore } from '@/stores/AppointmentStore';
import { useAnimalTypeStore } from '@/stores/AnimalTypeStore';
import { useDoctorStore } from '@/stores/DoctorStore';
import { useClientStore } from '@/stores/ClientStore';

export default {
  props: {
    userRoles: Array,
    editAppointment: Object || null,
  },
  setup(props) {
    const formatDateForInput = (dateString: string) => {
      const date = new Date(dateString);
      return date.toISOString().split('T')[0];
    };

    const appointmentStore = useAppointmentStore();
    const animalTypeStore = useAnimalTypeStore();
    const doctorStore = useDoctorStore();
    const clientStore = useClientStore();
    console.log('appointmentData', props.editAppointment);
    const animalAges = appointmentStore.animalAges;
    const animalName = ref(props.editAppointment?.animal_name || '');
    const animalTypeId = ref(props.editAppointment?.animal_type?.id || '');
    const animalAge = ref(props.editAppointment?.animal_age || '');
    const symptoms = ref(props.editAppointment?.symptoms || '');
    const appointmentDate = ref(
      props.editAppointment?.appointment_date 
        ? formatDateForInput(props.editAppointment.appointment_date) 
        : ''
    );    const appointmentTime = ref(props.editAppointment?.appointment_time || '');
    const doctorId = ref(props.editAppointment?.doctor_id || '');
    const userId = ref(props.editAppointment?.user_id || '');
    const errorMessages = ref<string[]>([]);
    const successMessage = ref('');
    const appointmentId = ref(props.editAppointment?.id || null);

    onMounted(() => {
      animalTypeStore.fetchAnimalTypes();
      if (props.userRoles?.includes('receptionist')) {
        doctorStore.fetchDoctors();
        clientStore.fetchClients();
      }
    });

    const createSubmit = async () => {
      errorMessages.value = [];
      try {
        await appointmentStore.createAppointment({
          animal_name: animalName.value,
          animal_type_id: animalTypeId.value,
          animal_age: animalAge.value,
          symptoms: symptoms.value,
          appointment_date: appointmentDate.value,
          appointment_time: appointmentTime.value,
          doctor_id: doctorId.value,
          user_id: userId.value,
        });
        successMessage.value = 'Agendamento realizado com sucesso!';
        resetForm();
      } catch (error: any) {
        handleErrors(error);
      }
    };

    const updateSubmit = async () => {
      if (!appointmentId.value) return;
      errorMessages.value = [];
      try {
        await appointmentStore.updateAppointment(appointmentId.value, {
          animal_name: animalName.value,
          animal_type_id: animalTypeId.value,
          animal_age: animalAge.value,
          symptoms: symptoms.value,
          appointment_date: appointmentDate.value,
          appointment_time: appointmentTime.value,
          doctor_id: doctorId.value,
        });
        successMessage.value = 'Agendamento atualizado com sucesso!';
      } catch (error: any) {
        handleErrors(error);
      }
    };

    const handleErrors = (error: any) => {
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
    };

    const resetForm = () => {
      animalName.value = '';
      animalTypeId.value = '';
      animalAge.value = '';
      symptoms.value = '';
      appointmentDate.value = '';
      appointmentTime.value = '';
      doctorId.value = '';
    };

    return {
      animalTypeStore,
      doctorId,
      userId,
      doctorStore,
      clientStore,
      appointmentId,
      createSubmit,
      updateSubmit,
      animalName,
      animalTypeId,
      animalAge,
      symptoms,
      appointmentDate,
      appointmentTime,
      errorMessages,
      successMessage,
      animalAges,
      userRoles: props.userRoles,
    };
  },
};
</script>
