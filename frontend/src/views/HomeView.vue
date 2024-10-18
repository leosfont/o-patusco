<template>
  <div class="min-h-screen bg-blue-100 flex flex-col justify-center items-center text-center py-10 px-4">
    <h1 class="text-5xl font-bold text-blue-600 mb-4">🐾 Bem-vindo à Clínica Veterinária O Patusco 🐶</h1>
    <p class="text-xl text-gray-600 mb-8">💖 Cuidando do seu pet com amor e dedicação. 🐱</p>

    <button 
      @click="openAppointmentModal" 
      class="bg-blue-500 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-blue-600 transition flex items-center space-x-2"
    >
      🐕 Agende sua primeira consulta
    </button>

    <p class="mt-4 text-gray-600">
      🔍 Quer ver suas consultas agendadas? <a @click="openRequestAccessModal" class="text-blue-500 hover:underline cursor-pointer">Solicite seu acesso aqui</a>.
    </p>

    <Modal :isOpen="showFirstAppointmentModal" @close="showFirstAppointmentModal = false">
      <template #title>🩺 Agende sua primeira consulta</template>
      <FirstAppointmentForm />
    </Modal>

    <Modal :isOpen="showAccessModal" @close="showAccessModal = false">
      <template #title>🔐 Solicitar Acesso</template>
      <RequestAccessForm />
    </Modal>
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';
import RequestAccessForm from '@/components/RequestAccessForm.vue';
import FirstAppointmentForm from '@/components/FirstAppointmentForm.vue';

export default {
  components: { Modal, FirstAppointmentForm, RequestAccessForm },
  setup() {
    const showFirstAppointmentModal = ref(false);
    const showAccessModal = ref(false);

    const openAppointmentModal = () => {
      showFirstAppointmentModal.value = true;
    };

    const openRequestAccessModal = () => {
      showAccessModal.value = true;
    };

    return {
      showFirstAppointmentModal,
      openAppointmentModal,
      showAccessModal,
      openRequestAccessModal,
    };
  },
};
</script>
