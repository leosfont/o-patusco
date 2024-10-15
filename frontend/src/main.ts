import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import './assets/main.css';
import axios from 'axios';
import { useLoaderStore } from './stores/LoaderStore';

const app = createApp(App);

app.use(router);
app.use(createPinia());

const loadingStore = useLoaderStore();

axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    loadingStore.setLoading(true);
    return config;
  },
  (error) => {
    loadingStore.setLoading(false);
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  (response) => {
    loadingStore.setLoading(false);
    return response;
  },
  (error) => {
    loadingStore.setLoading(false);
    return Promise.reject(error);
  }
);

app.mount('#app');
