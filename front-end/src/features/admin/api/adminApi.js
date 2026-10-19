import api from '../../../api/axios'

const adminApi = {
    async getStats() {
        const response = await api.get('/admin/stats')
        return response.data
    },
    async getUsers(params = {}) {
        const response = await api.get('/admin/users', { params })
        return response.data
    },
    async updateUser(id, data) {
        const response = await api.put(`/admin/users/${id}`, data)
        return response.data
    },
    async deleteUser(id) {
        const response = await api.delete(`/admin/users/${id}`)
        return response.data
    },
    async getJobs(params = {}) {
        const response = await api.get('/admin/jobs', { params })
        return response.data
    },
    async deleteJob(id) {
        const response = await api.delete(`/admin/jobs/${id}`)
        return response.data
    },
    async getCompanies(params = {}) {
        const response = await api.get('/admin/companies', { params })
        return response.data
    },
    async updateCompany(id, data) {
        const response = await api.patch(`/admin/companies/${id}`, data)
        return response.data
    },
    async deleteCompany(id) {
        const response = await api.delete(`/admin/companies/${id}`)
        return response.data
    },
}

export default adminApi
