import api from '../../../api/axios'

const applicationApi = {
    async apply(jobId, data) {
        const response = await api.post(`/jobs/${jobId}/applications`, data)
        return response.data
    },
    async getMine() {
        const response = await api.get('/applications')
        return response.data
    },
    async getManaged() {
        const response = await api.get('/employer/applications')
        return response.data
    },
    async updateStatus(id, status) {
        const response = await api.put(`/employer/applications/${id}`, { status })
        return response.data
    },
    async withdraw(id) {
        const response = await api.delete(`/applications/${id}`)
        return response.data
    },
}

export default applicationApi
