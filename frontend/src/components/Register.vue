<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h1 class="text-2xl font-semibold text-center text-gray-800 mb-6">Register</h1>
            <form @submit.prevent="register">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        placeholder="Enter your name"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="Enter your email"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        placeholder="Enter your password"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Register
                </button>
            </form>
            <p v-if="message" class="mt-4 text-center text-sm text-green-500">{{ message }}</p>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const form = ref({ name: '', email: '', password: '' });
        const message = ref('');

        const register = async () => {
            try {
                const response = await axios.post('http://localhost:8000/api/register', form.value);
                message.value = response.data.message;
            } catch (error) {
                console.error(error);
            }
        };

        return { form, message, register };
    },
};
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
