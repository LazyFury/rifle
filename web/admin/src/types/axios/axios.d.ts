import { AxiosRequestConfig } from "axios";


declare module 'axios' {
    // export interface AxiosInstance {
    //     request<T = any>(config: any): Promise<T>;
    //     get<T = any>(url: string, config?: any): Promise<T>;
    // }

    // export interface AxiosStatic {
    //     create(config?: any): AxiosInstance;
    // }

    export interface AxiosRequestConfig {
        binary?: boolean;
        noMsgAlert?: boolean;
    }

    // export interface AxiosResponse<T = any> {
    //     data: T;
    // }
}