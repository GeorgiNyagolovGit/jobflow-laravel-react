import axios from 'axios'
import { attachAuthInterceptor } from './interceptors/authInterceptor'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || '/api',
    headers: {
        Accept: 'application/json',
    },
    withCredentials: true,
    withXSRFToken: true,
})

attachAuthInterceptor(api)

export default api
