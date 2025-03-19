import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: (chunk) => {
                    if (chunk.name === "app") {
                        return "assets/app-CZfTQa7V.js"; // Nama tetap untuk JS
                    }
                    return "assets/[name].js";
                },
                chunkFileNames: "assets/[name].js",
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name === "app.css") {
                        return "assets/app-4wkGdzrB.css"; // Nama tetap untuk CSS
                    }
                    return "assets/[name].[ext]";
                },
            },
        },
    },
});
