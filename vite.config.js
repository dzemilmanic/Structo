import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/home.css',
                'resources/css/login.css',
                'resources/css/profile.css',
                'resources/css/auth.css',
                'resources/js/home.js',
                'resources/js/app.js',
                'resources/js/profile.js'
            ],
            refresh: true,
        }),
    ],
});