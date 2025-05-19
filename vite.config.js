import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/style.css',
                'resources/css/home.css',
                'resources/css/login.css',
                'resources/css/profile.css',
                'resources/js/app.js',
                'resources/js/profile.js'
            ],
            refresh: true,
        }),
    ],
});