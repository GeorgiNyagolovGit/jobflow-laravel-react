import axios from 'axios'
import { attachAuthInterceptor } from './interceptors/authInterceptor'

const configuredApiUrl = import.meta.env.VITE_API_URL?.replace(/\/$/, '')
const baseURL = configuredApiUrl
    ? `${configuredApiUrl.replace(/\/api$/, '')}/api`
    : '/api'

const api = axios.create({
    // Accept either https://api.example.com or https://api.example.com/api.
    // This removes a common production-only 404 caused by a missing /api suffix.
    baseURL,
    headers: {
        Accept: 'application/json',
    },
    withCredentials: true,
    withXSRFToken: true,
})

attachAuthInterceptor(api)

export default api
