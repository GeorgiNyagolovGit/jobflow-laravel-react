import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import JobForm from '../../components/JobForm/JobForm'
import jobApi from '../../api/jobApi'
import './CreateJobPage.css'

export default function CreateJobPage() {
    const navigate = useNavigate()
    const [submitting, setSubmitting] = useState(false)

    async function handleSubmit(data) {
        setSubmitting(true)

        try {
            const response = await jobApi.create(data)
            const job = response.job || response.data || response

            navigate(`/jobs/${job.id}`)
        } finally {
            setSubmitting(false)
        }
    }

    return (
        <main className="create-job-page">
            <h1>Create Job</h1>
            <p>Publish a new job opportunity.</p>
            <JobForm onSubmit={handleSubmit} submitting={submitting} />
        </main>
    )
}