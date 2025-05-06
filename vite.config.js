import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/reset.css',
                'resources/css/fonts.css',
                'resources/css/colors.css',
                'resources/css/icons.css',

                'resources/css/footer.css',
                'resources/css/header.css',

                'resources/css/auth.css',
                'resources/css/buttons.css',
                'resources/css/library.css',
                'resources/css/inputs.css',
                'resources/css/book.css',
                'resources/css/events.css',
                'resources/css/event.css',
                'resources/css/profile.css',

                'resources/js/app.js',
                'resources/js/library.js',
                'resources/js/event-map.js',
                'resources/js/auth-tabs.js',
                'resources/js/profile-dropdown.js',
                'resources/js/toggleSubscription.js'

            ],
            refresh: true,
        }),
    ],
});
