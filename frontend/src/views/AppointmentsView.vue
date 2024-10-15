<template>
  <div class="min-h-screen bg-blue-100 flex flex-col justify-start items-center py-10 px-4 text-center">
    <!-- Saudação e Botão de Sair -->
    <div class="w-full max-w-4xl mb-4 flex justify-between items-center">
      <p class="text-xl text-gray-600">Olá, {{ userName }} 👋</p>
      <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
        Sair
      </button>
    </div>

    <!-- Título -->
    <h1 class="text-4xl font-bold text-blue-600 mb-4">🐾 Suas Consultas</h1>
    <p class="text-xl text-gray-600 mb-8">✨ Gerencie suas consultas de forma simples e rápida. 😸</p>

    <!-- Filtros -->
    <div class="w-full max-w-4xl mb-6 bg-white p-4 rounded-lg shadow-lg flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0 items-center justify-center">
      <div class="w-full sm:w-auto flex-grow">
        <label for="filterType" class="block text-left mb-2 text-gray-700">🔍 Tipo de Animal</label>
        <select v-model="selectedAnimalType" id="filterType" class="border p-2 w-full rounded">
          <option value="">Selecione o tipo de animal</option>
          <option v-for="animalType in animalTypes" :key="animalType.id" :value="animalType.id">
            {{ animalType.name }}
          </option>
        </select>
      </div>
      <div class="w-full sm:w-auto flex-grow">
        <label for="filterDate" class="block text-left mb-2 text-gray-700">📅 Data</label>
        <input v-model="filterDate" id="filterDate" type="date" class="border p-2 w-full rounded" />
      </div>
    </div>

    <!-- Botões de Filtros -->
    <div class="flex justify-center space-x-4 mb-6">
      <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
        Aplicar Filtros
      </button>
      <button @click="clearFilters" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400 transition">
        Limpar Filtros
      </button>
    </div>

    <!-- Botão de Agendar Nova Consulta -->
    <button
      @click="openAppointmentModal"
      class="bg-blue-500 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-blue-600 transition mb-8 flex items-center"
    >
      🐶 Agendar Nova Consulta
    </button>

    <!-- Verifica se há consultas -->
    <div v-if="appointments.length === 0" class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-lg">
      <p class="text-gray-600 text-lg">Você ainda não agendou nenhuma consulta. 😿</p>
    </div>

    <!-- Grid de Consultas -->
    <div v-else class="w-full max-w-6xl space-y-6">
      <div 
        v-for="appointment in appointments" 
        :key="appointment.id" 
        class="bg-white p-6 rounded-lg shadow-lg flex flex-row justify-between items-center"
      >
        <!-- Informação da Consulta -->
        <div class="flex flex-col justify-start items-start">
          <h3 class="text-xl font-semibold text-blue-600 mb-2">{{ appointment.animal_name }}</h3>
          <p class="text-gray-700">🐾 Tipo: {{ appointment.animal_type?.name || 'Não especificado' }}</p>
          <p class="text-gray-700">📅 Data: {{ appointment.appointment_date }}</p>
          <p class="text-gray-700 capitalize">🕐 Turno: {{ translateTurno(appointment.appointment_time) }}</p>
          <p class="text-gray-700">👨‍⚕️ Médico: {{ appointment.doctor?.name || 'Não especificado' }}</p>
          <p class="text-gray-700">👤 Responsável: {{ appointment.responsible || 'Não especificado' }}</p>
        </div>
        
        <!-- Botões de Ações -->
        <div class="flex flex-col sm:flex-row items-center sm:space-x-4 mt-4 sm:mt-0">
          <button v-if="userRoles.includes('doctor') || userRoles.includes('receptionist')"
            @click="editAppointment(appointment)"
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

    <!-- Paginação -->
    <div class="mt-8">
      <button
        v-if="pagination.current_page > 1"
        @click="fetchPage(pagination.current_page - 1)"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2"
      >
        ⬅️ Anterior
      </button>
      <button
        v-if="pagination.current_page < pagination.last_page"
        @click="fetchPage(pagination.current_page + 1)"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
      >
        Próximo ➡️
      </button>
    </div>

    <!-- Modal de Agendamento -->
    <Modal :isOpen="showModal" @close="closeModal">
      <template #title>🐾 Agende uma Nova Consulta</template>
      <UserAppointmentForm @submit="handleFormSubmit" />
    </Modal>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Modal from '@/components/Modal.vue';
import UserAppointmentForm from '@/components/UserAppointmentForm.vue';
import { useRouter } from 'vue-router';

export default {
  components: {
    Modal,
    UserAppointmentForm,
  },
  setup() {
    const appointments = ref<any[]>([]);
    const pagination = ref({
      current_page: 1,
      last_page: 1,
      total: 0,
    });
    const animalTypes = ref<any[]>([]);
    const selectedAnimalType = ref<string>('');
    const filterDate = ref<string>('');
    const showModal = ref(false);
    const router = useRouter();

    const userName = ref<string>(''); 
    const userRoles = ref<string[]>([]); 

    const fetchUser = async () => {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`);
        userName.value = response.data.data.name;
        userRoles.value = response.data.data.roles;
      } catch (error) {
        console.error('Erro ao buscar o usuário:', error);
      }
    };

    
    const logout = () => {
      localStorage.removeItem('token'); 
      router.push('/'); 
    };

    
    const fetchAppointments = async (page = 1) => {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/user/appointments`, {
          params: {
            page,
            animal_type_id: selectedAnimalType.value || undefined,
            appointment_date: filterDate.value || undefined,
          },
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        appointments.value = response.data.data;
        pagination.value = {
          current_page: response.data.meta.current_page,
          last_page: response.data.meta.last_page,
          total: response.data.meta.total,
        };
      } catch (error) {
        console.error('Erro ao carregar as consultas.', error);
      }
    };

    
    const fetchAnimalTypes = async () => {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/animal-types`);
        animalTypes.value = response.data.data;
      } catch (error) {
        console.error('Erro ao carregar os tipos de animais.', error);
      }
    };

    const applyFilters = () => {
      fetchAppointments();
    };

    const clearFilters = () => {
      selectedAnimalType.value = '';
      filterDate.value = '';
      fetchAppointments();
    };

    const openAppointmentModal = () => {
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
    };

    const editAppointment = (appointment: any) => {
      console.log('Editar consulta:', appointment);
    };

    const deleteAppointment = async (id: string) => {
      try {
        await axios.delete(`${import.meta.env.VITE_API_URL}/user/appointments/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        fetchAppointments(pagination.value.current_page);
      } catch (error) {
        console.error('Erro ao excluir a consulta.', error);
      }
    };

    const fetchPage = (page: number) => {
      fetchAppointments(page);
    };

    const translateTurno = (time: string) => {
      return time === 'morning' ? 'Manhã' : 'Tarde';
    };

    const handleFormSubmit = () => {
      fetchAppointments();
      closeModal();
    };

    
    onMounted(() => {
      fetchUser();
      fetchAppointments();
      fetchAnimalTypes();
    });

    return {
      appointments,
      pagination,
      animalTypes,
      selectedAnimalType,
      filterDate,
      showModal,
      userName,
      applyFilters,
      clearFilters,
      openAppointmentModal,
      closeModal,
      editAppointment,
      deleteAppointment,
      fetchPage,
      translateTurno,
      handleFormSubmit,
      logout,
      userRoles,
    };
  },
};
</script>
