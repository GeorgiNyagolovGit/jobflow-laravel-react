import api from '../../../api/axios'

const companyApi = {
    async getAll(params = {}) {
        const response = await api.get('/companies', { params })
        return response.data
    },
    async getById(id) {
        const response = await api.get(`/companies/${id}`)
        return response.data
    },
    async getMine() {
        const response = await api.get('/my-company')
        return response.data
    },
    async create(data) {
        const response = await api.post('/companies', data)
        return response.data
    },
    async update(id, data) {
        const response = await api.put(`/companies/${id}`, data)
        return response.data
    },
}

export default companyApi
