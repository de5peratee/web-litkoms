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
                'resources/css/litar.css',
                'resources/css/pdf-viewer.css',

                'resources/css/editor/dashboard.css',
                'resources/css/editor/create_event.css',
                'resources/css/editor/create_post.css',
                'resources/css/editor/events_list.css',
                'resources/css/editor/multimedia_list.css',

                'resources/js/app.js',
                'resources/js/profile-dropdown.js',
                'resources/js/mobile-menu.js',
                'resources/js/library.js',
                'resources/js/events.js',
                'resources/js/auth-tabs.js',
                'resources/js/toggleSubscription.js',
                'resources/js/event-map.js',
                'resources/js/pdf-viewer.js',
            ],
            refresh: true,
        }),
    ],
});
