import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd());

    return {
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.js"],
                refresh: true,
            }),
            tailwindcss({
                config: {
                    darkMode: "class",
                },
            }),
        ],
        define: {
            "process.env": env, // ini penting biar bisa akses import.meta.env
        },
        server: {
            host: "0.0.0.0",
            hmr: {
                host: "192.168.1.68",
            },
            cors: {
                origin: "http://192.168.1.68:8000",
            },
        },
    };
});
