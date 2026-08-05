import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'node:path';
import tailwindcss from '@tailwindcss/vite';


export default defineConfig({
    plugins: [
        laravel({
        input: ['resources/css/app.css', 'resources/js/app.tsx'],
        ssr: 'resources/js/ssr.tsx',
        refresh: true,
        }),
        react({
            babel: {
                plugins: ['babel-plugin-react-compiler'],
            },
        }),
        tailwindcss(),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
        },   
    },

    build: {
        chunkSizeWarningLimit: 1000,
    },
        esbuild: {
        jsx: 'automatic',
    },
     server: {
        host: "127.0.0.1",
        hmr: {
            host: "127.0.0.1",
        },
    },

});