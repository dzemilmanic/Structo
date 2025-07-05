import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/admin_requests.css",
                "resources/css/admin-jobs.css",
                "resources/css/admin-users.css",
                "resources/css/home.css",
                "resources/css/dashboard.css",
                "resources/css/home.css",
                "resources/css/jobs.css",
                "resources/css/modal-details.css",
                "resources/css/news.css",
                "resources/css/policy.css",
                "resources/css/login.css",
                "resources/css/profile.css",
                "resources/css/qa.css",
                "resources/css/users.css",
                "resources/css/sweetalert-global.css",
                "resources/css/auth.css",
                "resources/css/style.css",
                "resources/css/user_profile.css",
                "resources/css/users.css",
                "resources/js/home.js",
                "resources/js/admin-jobs.js",
                "resources/js/admin-requests.js",
                "resources/js/allusers-admin.js",
                "resources/js/app.js",
                "resources/js/bootstrap.js",
                "resources/js/login.js",
                "resources/js/news.js",
                "resources/js/jobs.js",
                "resources/js/qa.js",
                "resources/js/users.js",
                "resources/js/profile.js",
            ],
            refresh: true,
        }),
    ],
});
