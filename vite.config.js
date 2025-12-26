import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/parentdashboard.css', 'resources/css/childprofile.css', 'resources/css/attendance.css', 'resources/css/help.css', 'resources/css/caregiver/dashboard.css',
                'resources/css/caregiver/events.css', 'resources/css/caregiver/notifications.css', 'resources/css/admin/dashboard.css', 'resources/css/admin/analytics.css', 'resources/css/admin/announcements.css', 'resources/css/admin/attendance.css', 'resources/css/admin/children.css', 'resources/css/admin/users.css', 'resources/css/admin/staff.css', 'resources/css/admin/reports.css', 'resources/css/admin/invoices.css', 'resources/css/admin/settings.css', 'resources/css/caregivers.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
