<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="Enter your email"
                        required
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
                        required
                        class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Login
                </button>
                <p v-if="errorMessage" class="mt-4 text-center text-sm text-red-500">{{ errorMessage }}</p>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
            },
            errorMessage: '',
        };
    },
    methods: {
        async submit() {
            try {
                const response = await axios.post('/login', this.form);
                const token = response.data.token;

                // Store token in localStorage
                localStorage.setItem('token', token);

                // Redirect to the homepage
                this.$router.push('/blogs');
            } catch (error) {
                console.error('Login error:', error);
                this.errorMessage = 'Invalid email or password. Please try again.';
            }
        },
    },
};
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
