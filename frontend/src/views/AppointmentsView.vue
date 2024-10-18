<template>
  <div class="min-h-screen bg-blue-100 flex flex-col justify-start items-center py-10 px-4 text-center">
    <div class="w-full max-w-4xl mb-4 flex justify-between items-center">
      <p class="text-xl text-gray-600">Olá, {{ userName }} 👋</p>
      <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
        Sair
      </button>
    </div>

    <h1 class="text-4xl font-bold text-blue-600 mb-4">🐾 Suas Consultas</h1>
    <p class="text-xl text-gray-600 mb-8">✨ Gerencie suas consultas de forma simples e rápida. 😸</p>

    <div class="w-full max-w-4xl mb-6 bg-white p-4 rounded-lg shadow-lg flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0 items-center justify-center">
      <div class="w-full sm:w-auto flex-grow">
        <label for="filterType" class="block text-left mb-2 text-gray-700">🔍 Tipo de Animal</label>
        <select v-model="selectedAnimalTypeId" id="filterType" class="border p-2 w-full rounded">
          <option value="">Selecione o tipo de animal</option>
          <option v-for="animalType in animalTypesStore.animalTypes" :key="animalType.id" :value="animalType.id">
            {{ animalType.name }}
          </option>
        </select>
      </div>
      <div class="w-full sm:w-auto flex-grow">
        <label for="filterDate" class="block text-left mb-2 text-gray-700">📅 Data</label>
        <input v-model="filterDate" id="filterDate" type="date" class="border p-2 w-full rounded" />
      </div>
    </div>

    <div class="flex justify-center space-x-4 mb-6">
      <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
        Aplicar Filtros
      </button>
      <button @click="clearFilters" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400 transition">
        Limpar Filtros
      </button>
    </div>

    <button
      v-if="userRoles.includes('client') || userRoles.includes('receptionist')"
      @click="openAppointmentModal"
      class="bg-blue-500 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-blue-600 transition mb-8 flex items-center"
    >
      🐶 Agendar Nova Consulta
    </button>

    <div v-if="appointmentsStore.appointments.length === 0" class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg">
      <p class="text-gray-600 text-lg">Você ainda não agendou nenhuma consulta. 😿</p>
    </div>

    <div v-else class="w-full max-w-6xl space-y-6">
      <div 
        v-for="appointment in appointmentsStore.appointments" 
        :key="appointment.id" 
        class="bg-white p-6 rounded-lg shadow-lg flex flex-row justify-between items-center"
      >
        <div class="flex flex-col justify-start items-start">
          <h3 class="text-xl font-semibold text-blue-600 mb-2">{{ appointment.animal_name }}</h3>
          <p class="text-gray-700">🐣 Idade: {{ appointment.animal_age }} {{ appointment.animal_age > 1 ? 'anos' : 'ano' }}</p>
          <p class="text-gray-700">🐾 Tipo: {{ appointment.animal_type?.name || 'Não especificado' }}</p>
          <p class="text-gray-700">📅 Data: {{ appointment.appointment_date_dmy }}</p>
          <p class="text-gray-700 capitalize">🕐 Turno: {{ translateTurno(appointment.appointment_time) }}</p>
          <p class="text-gray-700">👨‍⚕️ Médico: {{ appointment.doctor?.name || 'Não especificado' }}</p>
          <p class="text-gray-700">👤 Responsável: {{ appointment.responsible || 'Não especificado' }}</p>
          <p class="text-gray-700">📝 Sintomas: {{ appointment.symptoms || 'Não especificado' }}</p>

          
        </div>
        
        <div class="flex flex-col sm:flex-row items-center sm:space-x-4 mt-4 sm:mt-0">
          <button v-if="userRoles.includes('doctor') || userRoles.includes('receptionist')"
            @click="openAppointmentModal(appointment.id)"
            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition-colors flex items-center mb-2 sm:mb-0"
          >
            ✏️ Editar
          </button>
          <button v-if="userRoles.includes('receptionist')"
            @click="deleteAppointment(appointment.id)"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors flex items-center"
          >
            🗑️ Excluir
          </button>
        </div>
      </div>
    </div>

    <div class="mt-8">
      <button
        v-if="appointmentsStore.pagination.current_page > 1"
        @click="fetchPage(appointmentsStore.pagination.current_page - 1)"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2"
      >
        ⬅️ Anterior
      </button>
      <button
        v-if="appointmentsStore.pagination.current_page < appointmentsStore.pagination.last_page"
        @click="fetchPage(appointmentsStore.pagination.current_page + 1)"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
      >
        Próximo ➡️
      </button>
    </div>

    <Modal :isOpen="showAppointmentModal" @close="closeModal">
      <template #title>🐾 {{ editAppointment ? 'Atualize essa Consulta' : 'Agende uma Nova Consulta' }}</template>
      <AppointmentForm :editAppointment="editAppointment" :userRoles="userRoles" @close="closeModal" />
    </Modal>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import { useAppointmentStore } from '@/stores/AppointmentStore';
import { useAnimalTypeStore } from '@/stores/AnimalTypeStore';
import Modal from '@/components/Modal.vue';
import AppointmentForm from '@/components/AppointmentForm.vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/UserStore';
import type { Appointment } from '@/interfaces/Appointment';

export default {
  components: {
    Modal,
    AppointmentForm,
  },
  setup() {
    const appointmentsStore = useAppointmentStore();
    const animalTypesStore = useAnimalTypeStore();
    const userStore = useUserStore();
    const selectedAnimalTypeId = ref<string>('');
    const filterDate = ref<string>('');
    const showAppointmentModal = ref(false);
    const router = useRouter();
    const editAppointment = ref<Appointment|null>(null);

    const userName = ref<string>(''); 
    const userRoles = ref<string[]>([]); 

    const fetchUser = async () => {
      try {
        await userStore.fetchUser();
        userName.value = userStore.user?.name || '';
        userRoles.value = userStore.userRoles || [];

      } catch (error) {
        console.error('Erro ao buscar o usuário:', error);
      }
    };

    const logout = () => {
      localStorage.removeItem('token'); 
      router.push('/');
    };

    const applyFilters = () => {
      const page = 1;
      appointmentsStore.fetchAppointments(page, {
        animal_type_id: selectedAnimalTypeId.value,
        appointment_date: filterDate.value,
      });
    };

    const clearFilters = () => {
      selectedAnimalTypeId.value = '';
      filterDate.value = '';
      appointmentsStore.fetchAppointments();
    };

    const openAppointmentModal = (id: number | null = null) => {
      if (id) {
        editAppointment.value = appointmentsStore.appointments.find((a) => a.id === id);
      } else {
        editAppointment.value = null;
      }
      showAppointmentModal.value = true;
    };

    const closeModal = () => {
      showAppointmentModal.value = false;
    };

    const deleteAppointment = async (id: string) => {
      await appointmentsStore.deleteAppointment(id);
    };

    const fetchPage = (page: number) => {
      appointmentsStore.fetchAppointments(page);
    };

    const translateTurno = (time: string) => {
      return time === 'morning' ? 'Manhã' : 'Tarde';
    };

    const handleFormSubmit = () => {
      appointmentsStore.fetchAppointments();
      closeModal();
    };

    onMounted(() => {
      fetchUser();
      appointmentsStore.fetchAppointments();
      animalTypesStore.fetchAnimalTypes();
    });

    return {
      appointmentsStore,
      animalTypesStore,
      selectedAnimalTypeId,
      filterDate,
      showAppointmentModal,
      userName,
      applyFilters,
      clearFilters,
      openAppointmentModal,
      closeModal,
      deleteAppointment,
      fetchPage,
      translateTurno,
      handleFormSubmit,
      logout,
      editAppointment,
      userRoles,
    };
  },
};
</script>
