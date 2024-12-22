import { defineConfig } from 'vite';
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
});
