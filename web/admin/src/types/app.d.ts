

declare module '*.vue' {
  import { ComponentOptions } from 'vue'
  const componentOptions: ComponentOptions
  export default componentOptions
}


import { App } from 'vue';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $img: (url: string) => string;
  }
}

declare module 'vue' {
  interface App {
    $img: (url: string) => string;
  }
}