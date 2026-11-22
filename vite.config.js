import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/shuttle.css',
                'resources/js/shuttle/main.js',
                'resources/js/shuttle/about.js',
                'resources/js/shuttle/services.js',
                'resources/js/shuttle/cases.js',
                'resources/js/shuttle/contact.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});