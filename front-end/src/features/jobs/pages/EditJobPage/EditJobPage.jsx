import { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import JobForm from '../../components/JobForm/JobForm'
import jobApi from '../../api/jobApi'
import './EditJobPage.css'

export default function EditJobPage() {
    const { id } = useParams()
    const navigate = useNavigate()
    const [job, setJob] = useState(null)
    const [loading, setLoading] = useState(true)
    const [submitting, setSubmitting] = useState(false)
    const [error, setError] = useState('')

    useEffect(() => {
        jobApi.getById(id)
            .then((data) => setJob(data.job || data.data || data))
            .catch((requestError) => {
                setError(
                    requestError.response?.data?.message || 'Unable to load job.'
                )
            })
            .finally(() => setLoading(false))
    }, [id])

    async function handleSubmit(data) {
        setSubmitting(true)

        try {
            await jobApi.update(id, data)
            navigate(`/jobs/${id}`)
        } finally {
            setSubmitting(false)
        }
    }

    if (loading) {
        return <p>Loading job...</p>
    }

    if (error || !job) {
        return <p className="edit-job-error">{error || 'Job not found.'}</p>
    }

    return (
        <main className="edit-job-page">
            <h1>Edit Job</h1>
            <JobForm
                initialData={job}
                onSubmit={handleSubmit}
                submitting={submitting}
            />
        </main>
    )
}