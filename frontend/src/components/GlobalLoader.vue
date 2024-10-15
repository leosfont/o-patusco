<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="loader bg-white p-4 rounded-full animate-spin"></div>
    </div>
  </template>
  
  <script lang="ts">
  import { useLoaderStore } from '@/stores/LoaderStore';
  import { computed } from 'vue';
  
  export default {
    setup() {
      const loadingStore = useLoaderStore();
      const isLoading = computed(() => loadingStore.isLoading);
  
      return { isLoading };
    },
  };
  </script>
  
  <style scoped>
  .loader {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: blue;
  }
  </style>