import api from '../../../api/axios'

const profileApi = {
    async get() {
        const response = await api.get('/profile')
        return response.data
    },
    async update(data) {
        const response = await api.patch('/profile', data)
        return response.data
    },
    async updatePassword(data) {
        const response = await api.put('/settings/password', data)
        return response.data
    },
}

export default profileApi
