import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import UnoCSS from 'unocss/vite'


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js','resources/css/unocss.css'],
            refresh: true,
        }),
        UnoCSS({
            include: ['resources/views/**/*.blade.php'],
            config: 'unocss.config.js',
        }),
    ],
    server:{
        port:13712
    }
});
