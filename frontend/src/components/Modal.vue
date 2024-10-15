<template>
  <!-- Backdrop -->
  <div v-if="isOpen" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Content -->
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg max-h-full overflow-auto">
      <!-- Header -->
      <div class="flex justify-between items-center border-b pb-3">
        <h3 class="text-xl font-semibold"><slot name="title"></slot></h3>
        <!-- Close Button -->
        <button @click="closeModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
      </div>
      <!-- Modal Body -->
      <div class="mt-4">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  props: {
    isOpen: {
      type: Boolean,
      required: true,
    },
  },
  emits: ['close'],
  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
});
</script>
