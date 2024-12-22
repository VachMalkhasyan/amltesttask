<template>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">
            {{ blogId ? 'Edit Blog' : 'Create Blog' }}
        </h2>
        <form @submit.prevent="submit" class="space-y-4">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    placeholder="Enter blog title"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                />
                <p v-if="errors.title" class="text-red-500 text-sm">{{ errors.title }}</p>
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea
                    id="content"
                    v-model="form.content"
                    placeholder="Write your blog content..."
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                ></textarea>
                <p v-if="errors.content" class="text-red-500 text-sm">{{ errors.content }}</p>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input
                    type="file"
                    id="image"
                    @change="handleFile"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                />
            </div>

            <!-- Image Preview (if file selected) -->
            <div v-if="form.imagePreview" class="mt-4">
                <p class="text-gray-500 text-sm mb-2">Image Preview:</p>
                <img :src="form.imagePreview" alt="Image Preview" class="w-full h-auto rounded-lg" />
            </div>

            <!-- Display current image if editing an existing blog -->
            <div v-if="!form.imagePreview && form.currentImage" class="mt-4">
                <p class="text-gray-500 text-sm mb-2">Current Image:</p>
                <img :src="form.currentImage" alt="Current Image" class="w-full h-auto rounded-lg" />
            </div>

            <div class="flex space-x-4">
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                >
                    {{ blogId ? 'Update Blog' : 'Create Blog' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        id: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            form: {
                title: '',
                content: '',
                image: null,
                imagePreview: null,
                currentImage: null, // To hold the current image URL if editing
            },
            errors: {
                title: '',
                content: '',
            }
        };
    },
    computed: {
        blogId() {
            return this.id;
        },
    },
    mounted() {
        if (this.blogId) {
            this.loadBlog(this.blogId);
        }
    },
    methods: {
        // Fetch existing blog for editing
        async loadBlog(id) {
            try {
                const response = await axios.get(`/blogs/${id}`);
                this.form = {
                    ...response.data,
                    image: null, // Don't load image directly into the form field
                    imagePreview: response.data.image ? `/storage/${response.data.image}` : null,
                    currentImage: response.data.image ? `/storage/${response.data.image}` : null, // Set current image
                };
            } catch (error) {
                console.error('Error loading blog data:', error);
            }
        },

        // Handle image file selection and show preview
        handleFile(event) {
            const file = event.target.files[0];
            this.form.image = file;
            this.form.imagePreview = URL.createObjectURL(file); // Display a preview
        },

        // Submit the form
        async submit() {
            this.errors.title = '';
            this.errors.content = '';

            // Validation
            if (!this.form.title) {
                this.errors.title = 'The title field is required.';
            }
            if (!this.form.content) {
                this.errors.content = 'The content field is required.';
            }
            if (this.errors.title || this.errors.content) {
                return;
            }

            const formData = new FormData();
            formData.append('title', this.form.title);
            formData.append('content', this.form.content);
            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            if (this.blogId) {
                // Add `_method` to support Laravel PUT requests
                formData.append('_method', 'PUT');
            }

            try {
                console.log([...formData.entries()]); // Debug log

                const url = this.blogId ? `/blogs/${this.blogId}` : '/blogs';
                const method = this.blogId ? 'post' : 'post'; // Use POST for both cases

                await axios({
                    url,
                    method,
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.$router.push('/');
            } catch (error) {
                console.error('Error saving blog:', error);

                if (error.response && error.response.data && error.response.data.errors) {
                    const serverErrors = error.response.data.errors;
                    if (serverErrors.title) {
                        this.errors.title = serverErrors.title[0]; // Adapt to server response
                    }
                    if (serverErrors.content) {
                        this.errors.content = serverErrors.content[0]; // Adapt to server response
                    }
                }
            }
        }


    },
};
</script>

<style scoped>
/* Enhanced form styling for better UI */
.blog-form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.blog-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

label {
    font-size: 14px;
    color: #555;
    font-weight: 500;
}

.form-input,
.form-textarea,
.form-file {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
    transition: border-color 0.3s;
}

.form-input:focus,
.form-textarea:focus {
    border-color: #007bff;
    outline: none;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.btn-submit {
    padding: 12px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-submit:hover {
    background: #0056b3;
}

.btn-submit:active {
    background: #003f7f;
}

.text-red-500 {
    color: red;
}
</style>
