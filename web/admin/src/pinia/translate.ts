import { defineStore } from "pinia"
import { ref } from "vue"


export const useTranslateStore = defineStore('translate', () => {
    const supportedLocales = ref(['en', 'fr','zh-cn'])
    const localesToDisplay = ref({
        'en': 'English',
        'fr': 'Français',
        'zh-cn': '简体中文'
    })
    const locale = ref('zh-cn')
    const messages = ref({})

    function setLocale(newLocale) {
        if (!supportedLocales.value.includes(newLocale)) {
            throw new Error(`Locale ${newLocale} is not supported`)
        }
        locale.value = newLocale
    }

    function setMessages(key,_messages) {
        messages.value[key] = _messages
    }

    function getMessage(local){
        return messages.value[local] || {}
    }
    
    function getKey(key,args) {
        let msg = getMessage(locale.value)[key] || key + ""
        
        // format "hello {}" messages with the provided arguments
        try{
            if (Array.isArray(args) && args?.length >= 1) {
                for (let i = 0; i < args.length; i++) {
                    msg = msg.replace(/\{(.*?)\}/, args[i])
                }
            }

            // if obj
            if(typeof args === "object"){
                for (const key in args) {
                    msg = msg.replace(new RegExp(`\\{${key}\\}`,"g"), args[key])
                }
            }
            
            // single value 
            if(typeof args === "string" || typeof args === "number"){
                msg = msg.replace(/\{(.*?)\}/, args)
            }
        }catch(e){
            console.error("Translate Error:",e)
        }

        if(!msg){
            setTimeout(() => {
                // 仅上报,push 到服务器配置翻译，初始化的时候再获取
                console.log(`key:${key} not found in locale:${locale.value}`)
            }, 100);
        }
        return msg
    }

    function getLocaleToDisplay(_locale) {
        if(!_locale){
            _locale = locale.value
        }
        return localesToDisplay.value[_locale] || _locale
    }

    return { locale, messages, setLocale, setMessages, getKey,getLocaleToDisplay }
})

export default useTranslateStore