import axios from 'axios'
import config from '../config'
import { ElMessage } from 'element-plus';
import router from '../router';
import NProgress from 'nprogress'

const createAxiosInstance = (baseURL,opt={}) => {
    const instance = axios.create({
        baseURL: baseURL,
        timeout: 30000,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        binary:false,
        ...opt
    });

    instance.interceptors.request.use(function (config) {
        let token = localStorage.getItem('token')
        if (token) {
            config.headers.Token = '' + token
        }
        console.log("请求参数：",config)
        NProgress.start()
        return config;
    })

    instance.interceptors.response.use(function (response) {
        NProgress.done()
        if(response.config.binary){
            return response
        }
        // Do something with response data
        let resp = response.data
        let msg = resp.message || resp.msg || '未知错误'
        let code = resp.code || 0
        if (code !== 200) {
            ElMessage.error(msg || response.statusText)
        }
        return response;
    }, function (error) {
        NProgress.done()
        if(error?.config?.binary){
            return Promise.reject(error)
        }
        // Do something with response error
        console.log(error)
        let resp = error.response
        if(!resp) throw new Error("网络错误")
        let msg = resp.data?.message || resp.data?.msg || '未知错误'
        let code = resp.data?.code || resp.status || -1
        if(!resp.config?.noMsgAlert){
            ElMessage.error(msg || error.message)
        }
        if (code === 1007) {
            setTimeout(() => {
                router.push('/login')
            }, 100);
        }

        

        return Promise.reject(error);
    });

    return instance
}

export const request = createAxiosInstance(config.url.API_URL) 