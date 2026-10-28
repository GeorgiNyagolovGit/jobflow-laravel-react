import api from '../../../api/axios'

const favoriteApi = {
    async getAll() {
        const response = await api.get('/favorites')
        return response.data
    },
    async add(jobId) {
        const response = await api.post(`/jobs/${jobId}/favorite`)
        return response.data
    },
    async remove(jobId) {
        const response = await api.delete(`/jobs/${jobId}/favorite`)
        return response.data
    },
}

export default favoriteApi
