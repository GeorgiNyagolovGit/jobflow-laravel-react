import { useCallback, useEffect, useState } from 'react'
import companyApi from '../api/companyApi'

export default function useCompanies(params = {}) {
    const [companies, setCompanies] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')

    const loadCompanies = useCallback(async () => {
        setLoading(true)
        try {
            const data = await companyApi.getAll(params)
            setCompanies(data.companies?.data || data.companies || data.data || data)
            setError('')
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Unable to load companies.')
        } finally { setLoading(false) }
    }, [params])

    useEffect(() => { loadCompanies() }, [loadCompanies])
    return { companies, loading, error, reload: loadCompanies }
}
