<template>
    <div id="app" class="bg-gray-50 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4 shadow-md">
            <nav class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex space-x-4">
                    <!-- Show Register and Login if not authenticated -->
                    <template v-if="!isAuthenticated">
                        <router-link
                            to="/register"
                            class="text-white hover:text-blue-200 transition duration-200"
                        >
                            Register
                        </router-link>
                        <router-link
                            to="/login"
                            class="text-white hover:text-blue-200 transition duration-200"
                        >
                            Login
                        </router-link>
                    </template>

                    <!-- Show My Blogs and Logout if authenticated -->
                    <template v-if="isAuthenticated">
                        <router-link
                            to="/blogs"
                            class="text-white hover:text-blue-200 transition duration-200"
                        >
                            Blogs
                        </router-link>
                        <router-link
                            to="/blogs/create"
                            class="text-white hover:text-blue-200 transition duration-200"
                        >
                            Create Blog
                        </router-link>
                        <router-link
                            to="/my-blogs"
                            class="text-white hover:text-blue-200 transition duration-200"
                        >
                            My Blogs
                        </router-link>
                    </template>
                </div>

                <!-- Logout Button -->
                <div v-if="isAuthenticated">
                    <button
                        @click="logout"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200"
                    >
                        Logout
                    </button>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow py-8">
            <div class="max-w-7xl mx-auto px-4">
                <RouterView /> <!-- This is where the route components will be displayed -->
            </div>
        </main>

        <!-- Footer (optional) -->
        <footer class="bg-blue-600 text-white text-center py-4 mt-auto">
            <p class="text-sm">© 2024 Your Blog, All Rights Reserved</p>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // Initialize isAuthenticated to check if token exists
            isAuthenticated: localStorage.getItem('token') !== null,
        };
    },
    methods: {
        logout() {
            // Remove the token from localStorage and set isAuthenticated to false
            localStorage.removeItem('token');
            this.isAuthenticated = false; // Update the reactive data
            this.$router.push('/login'); // Redirect to login
        },
    },
    watch: {
        // Watch for changes to localStorage and update isAuthenticated
        '$route'(to, from) {
            // Re-check the authentication state whenever the route changes
            this.isAuthenticated = localStorage.getItem('token') !== null;
        },
    },
    created() {
        // Re-check the authentication status when the component is created
        this.isAuthenticated = localStorage.getItem('token') !== null;
    },
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
