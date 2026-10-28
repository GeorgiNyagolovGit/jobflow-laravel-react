import api from '../../../api/axios'

const jobApi = {
    async getAll(params = {}) {
        const response = await api.get('/jobs', { params })
        return response.data
    },

    async getById(id) {
        const response = await api.get(`/jobs/${id}`)
        return response.data
    },

    async create(data) {
        const response = await api.post('/jobs', data)
        return response.data
    },

    async update(id, data) {
        const response = await api.put(`/jobs/${id}`, data)
        return response.data
    },

    async delete(id) {
        const response = await api.delete(`/jobs/${id}`)
        return response.data
    },

    async getMyJobs() {
        const response = await api.get('/my-jobs')
        return response.data
    },
}

export default jobApi
