import { defineConfig } from 'vite';
<<<<<<< HEAD
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [vue()],
    css: {
        preprocessorOptions: {
            css: {
                additionalData: `@import './src/index.css';`,
            },
        },
    },
=======
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
>>>>>>> 559d2f6 (final)
});
