import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/a_homepage.js',
                'resources/js/a_inquiries.js',
                'resources/js/a_report.js',
                'resources/js/a_reserve.js',
                'resources/js/admin_profile.js',
                'resources/js/faqs.js',
                'resources/js/login.js',
                'resources/js/p_homepage.js',
                'resources/js/p_mreserve.js',
                'resources/js/p_vreserve.js',
                'resources/js/payment_order.js',
                'resources/js/signup.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
