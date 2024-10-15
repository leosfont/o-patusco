import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import AppointmentsView from '@/views/AppointmentsView.vue';
import { useAuthStore } from '@/stores/AuthStore';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/user/appointments',
    name: 'appointments',
    component: AppointmentsView,
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore();
      if (!authStore.token && !localStorage.getItem('token')) {
        next('/');
      } else {
        next();
      }
    },
  },
  {
    path: '/login/:token',
    name: 'loginWithToken',
    beforeEnter: async (to, from, next) => {
      const authStore = useAuthStore();
      try {
        // Autentica o usuário com o token da URL
        await authStore.loginWithToken(to.params.token);
        next('/user/appointments'); // Redireciona para appointments após o login
      } catch (error) {
        console.error('Erro ao autenticar com o token:', error);
        next('/');
      }
    },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
