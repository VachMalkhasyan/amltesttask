<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Blogs</h2>

            <!-- Blog List -->
            <div v-if="blogs.length > 0">
                <div
                    v-for="blog in blogs"
                    :key="blog.id"
                    class="bg-white shadow-lg rounded-lg mb-6 overflow-hidden hover:shadow-2xl transition-shadow duration-300 cursor-pointer"
                    @click="openModal(blog)"
                >
                    <!-- Display Image -->
                    <img
                        :src="`http://localhost:8000/storage/${blog.image}`"
                        alt="Blog Image"
                        class="w-full h-56 object-cover"
                    />

                    <!-- Blog Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ blog.title }}</h3>
                        <p class="text-gray-600 mb-4">{{ blog.content }}</p>
                        <div class="flex justify-between text-sm text-gray-500">
                            <p class="italic">Created by: {{ blog.user.name }}</p>
                            <p>{{ new Date(blog.created_at).toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-8">
                    <button
                        :disabled="!pagination.prev_page_url"
                        @click="getBlogs(pagination.prev_page_url)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        &laquo; Previous
                    </button>

                    <p class="text-gray-700">
                        Page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </p>

                    <button
                        :disabled="!pagination.next_page_url"
                        @click="getBlogs(pagination.next_page_url)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Next &raquo;
                    </button>
                </div>
            </div>

            <!-- No blogs message -->
            <div v-else>
                <p class="text-center text-gray-600">No blogs available.</p>
            </div>
        </div>

        <!-- Modal -->
        <div
            v-if="modalVisible"
            @click.self="closeModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg w-4/5 md:w-3/4 lg:w-2/3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ selectedBlog.title }}</h3>
                <img
                    :src="`http://localhost:8000/storage/${selectedBlog.image}`"
                    alt="Full Blog Image"
                    class="w-full h-auto mb-4 rounded-lg"
                />
                <p class="text-gray-600 mb-4">{{ selectedBlog.content }}</p>
                <div class="flex justify-between text-sm text-gray-500">
                    <p class="italic">Created by: {{ selectedBlog.user.name }}</p>
                    <p>{{ new Date(selectedBlog.created_at).toLocaleString() }}</p>
                </div>
                <button
                    @click="closeModal"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            blogs: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                next_page_url: null,
                prev_page_url: null,
            },
            modalVisible: false,
            selectedBlog: null,
        };
    },
    mounted() {
        this.getBlogs('http://localhost:8000/api/blogs?page=1');
    },
    methods: {
        async getBlogs(url) {
            try {
                const response = await axios.get(url);
                this.blogs = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    next_page_url: response.data.next_page_url,
                    prev_page_url: response.data.prev_page_url,
                };
            } catch (error) {
                console.error('Error fetching blogs:', error);
            }
        },
        openModal(blog) {
            this.selectedBlog = blog;
            this.modalVisible = true;
        },
        closeModal() {
            this.modalVisible = false;
            this.selectedBlog = null;
        },
    },
};
</script>


<style scoped>
/* You can now remove the custom styles as Tailwind CSS handles most of the styling */
</style>
