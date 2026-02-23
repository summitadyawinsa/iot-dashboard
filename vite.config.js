import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import fs from "fs";

export default defineConfig({
    server: {
        host: "dashboard.summitadyawinsa.co.id",
        port: 8001,
        https: {
            key: fs.readFileSync(
                "/etc/ssl/company/star_summitadyawinsa_co_id_cert.key"
            ),
            cert: fs.readFileSync("/etc/ssl/company/fullchain.pem"),
        },
        cors: true,
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/highlight.min.css",
                "resources/css/easymde.min.css",
                "resources/css/fancybox.css",
                "resources/css/flatpickr.min.css",
                "resources/css/font-awesome.min.css",
                "resources/css/fullcalendar.min.css",
                "resources/css/highlight.min.css",
                "resources/css/markdown-editor.css",
                "resources/css/nice-select.css",
                "resources/css/nice-select2.css",
                "resources/css/nouislider.min.css",
                "resources/css/quill.snow.css",
                "resources/css/swiper-bundle.min.css",
                "resources/css/tippy.css",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
