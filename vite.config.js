import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/auth.css',
                'resources/css/buttons.css',
                'resources/css/colors.css',
                'resources/css/fonts.css',
                'resources/css/footer.css',
                'resources/css/header.css',
                'resources/css/library.css',
                'resources/css/reset.css',
                'resources/css/inputs.css',

                'resources/js/app.js',
                'resources/js/auth-tabs.js'

            ],
            refresh: true,
        }),
    ],
});
