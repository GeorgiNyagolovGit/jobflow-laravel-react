import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import jobApi from '../../api/jobApi'
import './JobDetailsPage.css'

export default function JobDetailsPage() {
    const { id } = useParams()
    const [job, setJob] = useState(null)
    const [loading, setLoading] = useState(true)
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

    if (loading) {
        return <p className="job-details-message">Loading job...</p>
    }

    if (error || !job) {
        return <p className="job-details-error">{error || 'Job not found.'}</p>
    }

    return (
        <main className="job-details-page">
            <Link to="/jobs" className="job-back-link">Back to jobs</Link>

            <h1>{job.title}</h1>
            <p className="job-details-company">
                {job.company?.name || job.company_name || 'Company not specified'}
            </p>

            <div className="job-details-meta">
                <span>{job.location || 'Location not specified'}</span>
                <span>{job.job_type?.name || job.employment_type || 'Employment type not specified'}</span>
                {(job.salary || job.salary_min) && <span>{job.salary || `${job.salary_min}${job.salary_max ? ` – ${job.salary_max}` : ''} ${job.salary_currency || ''}`}</span>}
            </div>

            <Link className="primary-link" to={`/jobs/${job.id}/apply`}>Apply now</Link>

            <section>
                <h2>Job Description</h2>
                <p className="job-details-text">{job.description}</p>
            </section>

            {job.requirements && (
                <section>
                    <h2>Requirements</h2>
                    <p className="job-details-text">{job.requirements}</p>
                </section>
            )}
        </main>
    )
}
