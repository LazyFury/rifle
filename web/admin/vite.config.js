import { fileURLToPath, URL } from 'node:url'

import { defineConfig,loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import UnoCSS from 'unocss/vite'
// iconify
import Icons from 'unplugin-icons/vite'
import IconsResolver from 'unplugin-icons/resolver'
import Components from 'unplugin-vue-components/vite'
// https://vitejs.dev/config/
export default (({mode})=>{
  const env = loadEnv(mode, process.cwd())
  return defineConfig({
    plugins: [
      vue(),
      vueJsx(),
      UnoCSS(),
      Icons(),
      Components({
          resolvers:[
              IconsResolver()
          ]
      })
    ],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      }
    },
    server:{
      port:env.VITE_PORT || 12391,
    }
  })
})
