<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">My Blogs</h2>
        <div v-if="blogs.length > 0">
            <div v-for="blog in blogs" :key="blog.id" class="flex items-center justify-between border-b pb-4 mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ blog.title }}</h3>
                    <p class="text-sm text-gray-600">{{ blog.content.slice(0, 100) }}...</p>
                </div>
                <div class="flex space-x-4">
                    <!-- Update Button -->
                    <router-link :to="{ name: 'update-blog', params: { id: blog.id } }">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600">
                            Update
                        </button>
                    </router-link>


                    <!-- Delete Button -->
                    <button
                        @click="deleteBlog(blog.id)"
                        class="px-4 py-2 bg-red-500 text-white rounded-md shadow-md hover:bg-red-600"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No blogs found.</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            blogs: [],
        };
    },
    mounted() {
        this.loadBlogs();
    },
    methods: {
        async loadBlogs() {
            try {
                const response = await axios.get('/user/blogs'); // Adjust API URL as necessary
                this.blogs = response.data.data;
            } catch (error) {
                console.error('Error loading blogs:', error);
            }
        },
        async deleteBlog(id) {
            try {
                const confirmDelete = window.confirm('Are you sure you want to delete this blog?');
                if (confirmDelete) {
                    await axios.delete(`/blogs/${id}`);
                    this.blogs = this.blogs.filter(blog => blog.id !== id); // Remove deleted blog from list
                }
            } catch (error) {
                console.error('Error deleting blog:', error);
            }
        },
    },
};
</script>

<style scoped>
h2 {
    text-align: center;
    color: #333;
}

.blog-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

button {
    transition: background-color 0.3s ease;
}
</style>
