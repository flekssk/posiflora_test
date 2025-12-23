import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from "laravel-vite-plugin";
import { fileURLToPath, URL } from 'node:url'

export default defineConfig(({mode}) => {
    const isDebug = mode === 'debug' || process.env.DEBUG_BUILD === '1'

    return {
        plugins: [
            laravel({
                input: ['resources/js/app.js'], // тот же путь, что в Blade
                refresh: true,
            }),
            vue(),
        ],
        resolve: {
            alias: {
                "@": fileURLToPath(new URL("./resources/js", import.meta.url)),
            },
        },
        build: {
            // Отключить минификацию (обфускацию)
            minify: isDebug ? false : 'esbuild',
            // Для Vite 5+ можно отдельно отключить минификацию CSS
            cssMinify: !isDebug,
            // Источники карт для удобного дебага
            sourcemap: isDebug,

            // Сохранять имена классов/функций даже если минификация включена
            esbuild: {
                keepNames: true,
            },

            // Убрать хэш из имён файлов, чтобы проще было сопоставлять чанки
            rollupOptions: isDebug
                ? {
                    output: {
                        entryFileNames: 'assets/[name].js',
                        chunkFileNames: 'assets/[name].js',
                        assetFileNames: 'assets/[name][extname]',
                    },
                }
                : undefined,
        },
        css: {
            // Полезно иметь карты и для стилей
            devSourcemap: true,
        },
    }
})
