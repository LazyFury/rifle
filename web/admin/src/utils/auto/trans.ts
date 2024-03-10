import useTranslateStore from "@/pinia/translate.js"

let _instance = null
const translateStoreInstance = () => {
    if (!_instance) {
        _instance = useTranslateStore()
    }
    return _instance
}

export const trans = (key: string, ...args: any[]) => {
    return useTranslateStore().getKey(key, ...args)
}
export const $t = trans