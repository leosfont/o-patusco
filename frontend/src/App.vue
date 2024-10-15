<script setup lang="ts">
import { RouterView } from 'vue-router'
</script>

<template>
  <GlobalLoader />
  <RouterView />
</template>

<script lang="ts">
import GlobalLoader from '@/components/GlobalLoader.vue';

export default {
  components: {
    GlobalLoader,
  },
};
</script>
