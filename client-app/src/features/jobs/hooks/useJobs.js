import { useCallback, useEffect, useState } from 'react'
import jobApi from '../api/jobApi'

export default function useJobs(initialParams = {}) {
    const [jobs, setJobs] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')

    const loadJobs = useCallback(async (params = initialParams) => {
        setLoading(true)
        setError('')

        try {
            const response = await jobApi.getAll(params)
            setJobs(response.jobs?.data || response.data || response.jobs || response)
        } catch (requestError) {
            setError(
                requestError.response?.data?.message || 'Unable to load jobs.'
            )
        } finally {
            setLoading(false)
        }
    }, [initialParams])

    useEffect(() => {
        loadJobs()
    }, [loadJobs])

    return {
        jobs,
        loading,
        error,
        loadJobs,
        setJobs,
    }
}
