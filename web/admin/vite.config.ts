import { fileURLToPath } from 'url'

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import UnoCSS from 'unocss/vite'
// iconify
import Icons from 'unplugin-icons/vite'
import IconsResolver from 'unplugin-icons/resolver'
import Components from 'unplugin-vue-components/vite'

// unplugin 
import AutoImport from 'unplugin-auto-import/vite'

// https://vitejs.dev/config/
export default (({ mode }) => {
  const env = loadEnv(mode, process.cwd())
  return defineConfig({
    plugins: [
      vue({
        include: [/\.vue$/, /\.md$/]
      }),
      vueJsx({}),
      UnoCSS(),
      Icons(),
      Components({
        resolvers: [
          IconsResolver()
        ]
      }),
      AutoImport({
        include: [
          /\.[tj]sx?$/, // .ts, .tsx, .js, .jsx
          /\.vue\??/, // .vue
          // api 
        ],
        dts: true,
        imports: [
          "vue",
          "vue-router",
        ],
        dirs: [
          "./src/api"
        ]
      })
    ],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      }
    },
    server: {
      port: Number(env.VITE_PORT) || 12391,
    },
    build: {
      // 分包
      chunkSizeWarningLimit: 2048,
      // 给 element-plus 分割为单独的 chunk
      rollupOptions: {
        output: {
          manualChunks: {
            'element-plus': ['element-plus'],
            "antv-g2": ["@antv/g2"],
            "vue": ["vue"],
            "vue-router": ["vue-router"],
            "axios": ["axios"],
            "dayjs": ["dayjs"],
            "numeral": ["numeral"],
            "nprogress": ["nprogress"],
            "pinia": ["pinia"],
          }
        }
      }
    }
  })
})
