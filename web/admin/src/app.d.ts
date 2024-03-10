export { }

declare module '*.vue' {


  interface ComponentCustomProperties {
    $store: import("pinia").Pinia
    trans: (key: string, ...args: any[]) => string
  }

  import { ComponentOptions } from 'vue'
  const componentOptions: ComponentOptions
  export default componentOptions

}

