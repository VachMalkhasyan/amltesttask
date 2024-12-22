import { createRouter, createWebHistory } from 'vue-router';
import BlogList from './components/BlogList.vue'; // Blog List Component
import BlogForm from './components/BlogForm.vue'; // Blog Form (Create/Edit) Component
import Login from './components/Login.vue'; // Login Component
import Register from './components/Register.vue'; // Register Component
import MyBlogs from './components/MyBlogs.vue'; // My Blogs Component (List of user's blogs)

const routes = [
    {
        path: '/blogs',
        name: 'home',
        component: BlogList, // Home route renders BlogList
    },
    {
        path: '/blogs/create',
        name: 'create-blog',
        component: BlogForm, // Create Blog route
        meta: { requiresAuth: true }, // Protect this route if authentication is required
    },
    {
        path: '/blogs/edit/:id',
        name: 'edit-blog',
        component: BlogForm, // Edit Blog route
        props: true, // Pass route params (id) as props to the component
        meta: { requiresAuth: true }, // Protect this route if authentication is required
    },
    {
        path: '/blogs/update/:id',
        name: 'update-blog',
        component: BlogForm,
        props: true, // This will pass the 'id' as a prop to the BlogUpdate component
    },
    {
        path: '/my-blogs',
        name: 'my-blogs',
        component: MyBlogs, // My Blogs route
        meta: { requiresAuth: true }, // Protect this route if authentication is required
    },
    {
        path: '/login',
        name: 'login',
        component: Login, // Login route
    },
    {
        path: '/register',
        name: 'register',
        component: Register, // Register route
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Add navigation guards for protected routes (e.g., for login, create, and edit blog)
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token'); // Check if the user has a token
    if (to.meta.requiresAuth && !token) {
        next({ name: 'login' });
    } else {
        next();
    }
});

export default router;
