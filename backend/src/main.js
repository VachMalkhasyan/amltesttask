import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios';
import router from './router'; // Import Vue Router
import './index.css';
//in your `main.js` file
import '../node_modules/flowbite-vue/dist/index.css'


// Set Axios default base URL
axios.defaults.baseURL = 'http://localhost:8000/api';

// Axios request interceptor to attach Authorization header
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token'); // Fetch the token from local storage
        if (token) {
            config.headers.Authorization = `Bearer ${token}`; // Attach token to headers
        }
        return config;
    },
    (error) => {
        return Promise.reject(error); // Handle request error
    }
);

// Axios response interceptor to handle global errors (e.g., 401 Unauthorized)
axios.interceptors.response.use(
    (response) => response, // Pass through successful responses
    (error) => {
        if (error.response && error.response.status === 401) {
            // Handle 401 Unauthorized errors
            localStorage.removeItem('token'); // Clear token
            router.push({ name: 'Login' }); // Redirect to login page
        }
        return Promise.reject(error); // Pass other errors to be handled locally
    }
);

const app = createApp(App);

// Use Vue Router
app.use(router);

// Mount the Vue app
app.mount('#app');
