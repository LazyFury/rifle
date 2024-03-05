
const prod = {
    url: {
        API_URL: 'http://tt.lazyfury.fun/api',
        BASE_URL: 'https://yourdomain.com',
        IMG_URL: 'https://yourdomain.com',
    }
}

const dev = {
    url: {
        API_URL: 'http://localhost:8000/api',
        BASE_URL: 'http://localhost:8000',
        IMG_URL: 'http://localhost:8000/',
    }
}

const debug = import.meta.env.MODE === 'development'

export const config = debug ? dev : prod
export default config