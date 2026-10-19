import api from '../../../api/axios'

const authApi = {
    async getCsrfCookie() {
        await api.get('/csrf-cookie')
    },

    async login(data) {
        await authApi.getCsrfCookie()
        const response = await api.post('/login', data)
        return response.data
    },

    async register(data) {
        await authApi.getCsrfCookie()
        const response = await api.post('/register', data)
        return response.data
    },

    async logout() {
        await authApi.getCsrfCookie()
        const response = await api.post('/logout')
        return response.data
    },

    async getUser() {
        const response = await api.get('/user')
        return response.data
    },

    async forgotPassword(data) {
        const response = await api.post('/forgot-password', data)
        return response.data
    },

    async resetPassword(data) {
        const response = await api.post('/reset-password', data)
        return response.data
    },

    async resendVerification() {
        const response = await api.post('/email/verification-notification')
        return response.data
    },
}

export default authApi
