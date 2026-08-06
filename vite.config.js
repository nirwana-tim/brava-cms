import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { local } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                local('General Sans', {
                    variants: [
                        { src: 'resources/fonts/general-sans-400.woff2', weight: 400 },
                        { src: 'resources/fonts/general-sans-500.woff2', weight: 500 },
                        { src: 'resources/fonts/general-sans-600.woff2', weight: 600 },
                    ],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
