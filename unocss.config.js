// uno.config.ts
import { defineConfig,presetAttributify,presetUno,presetTypography,presetWebFonts } from 'unocss'

export default defineConfig({
  // ...UnoCSS options
    presets: [
        presetAttributify(),
        presetUno(),
        presetTypography(),
        presetWebFonts({
            sans: ['Inter', 'sans-serif'],
            serif: ['Georgia', 'serif'],
            mono: ['Menlo', 'monospace'],
        }),
    ],
    cli:{
        entry:{
            patterns:['resources/views/**/*.blade.php'],
            extensions:['html','blade.php'],
            outFile:'resources/css/unocss.css',
        }
    },
    shortcuts:{
        "main-container":"container mx-auto xl:w-1200px"
    }
})