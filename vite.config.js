import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '172.20.10.9', // e.g. 192.168.1.42
        },
    },
});