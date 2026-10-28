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
    async uploadCv(file) {
        const data = new FormData()
        data.append('cv', file)

        const response = await api.post('/profile/cv', data)
        return response.data
    },
    async deleteCv() {
        const response = await api.delete('/profile/cv')
        return response.data
    },
    cvDownloadUrl() {
        return api.getUri({ url: '/profile/cv' })
    },
    async updatePassword(data) {
        const response = await api.put('/settings/password', data)
        return response.data
    },
}

export default profileApi
