import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from "laravel-vite-plugin";
import { fileURLToPath, URL } from 'node:url'

export default defineConfig(({mode}) => {
    const isDebug = mode === 'debug' || process.env.DEBUG_BUILD === '1'

    return {
        plugins: [
            laravel({
                input: ['resources/js/app.js'],
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
            minify: isDebug ? false : 'esbuild',
            cssMinify: !isDebug,
            sourcemap: isDebug,

            esbuild: {
                keepNames: true,
            },

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
            devSourcemap: true,
        },
    }
})
