import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import jobApi from '../../api/jobApi'
import './MyJobsPage.css'

export default function MyJobsPage() {
    const [jobs, setJobs] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')
    const [deletingId, setDeletingId] = useState(null)

    async function loadJobs() {
        try {
            const data = await jobApi.getMyJobs()
            setJobs(data.data || data.jobs || data)
            setError('')
        } catch (requestError) {
            setError(
                requestError.response?.data?.message || 'Unable to load your jobs.'
            )
        } finally {
            setLoading(false)
        }
    }

    useEffect(() => {
        loadJobs()
    }, [])

    async function handleDelete(id) {
        if (!window.confirm('Are you sure you want to delete this job?')) {
            return
        }

        setDeletingId(id)

        try {
            await jobApi.delete(id)
            setJobs((current) => current.filter((job) => job.id !== id))
        } catch (requestError) {
            setError(
                requestError.response?.data?.message || 'Unable to delete job.'
            )
        } finally {
            setDeletingId(null)
        }
    }

    if (loading) {
        return <p className="my-jobs-message">Loading your jobs...</p>
    }

    return (
        <main className="my-jobs-page">
            <div className="my-jobs-header">
                <h1>My Jobs</h1>
                <Link to="/jobs/create" className="my-jobs-create">
                    Create Job
                </Link>
            </div>

            {error && <p className="my-jobs-error">{error}</p>}

            {!jobs.length && !error && (
                <p className="my-jobs-message">You haven't posted any jobs yet.</p>
            )}

            <div className="my-jobs-list">
                {jobs.map((job) => (
                    <article key={job.id} className="my-job-item">
                        <div>
                            <h2>{job.title}</h2>
                            <p>{job.location || 'Location not specified'}</p>
                        </div>

                        <div className="my-job-actions">
                            <Link to={`/jobs/${job.id}`}>View</Link>
                            <Link to={`/jobs/${job.id}/edit`}>Edit</Link>
                            <button
                                type="button"
                                onClick={() => handleDelete(job.id)}
                                disabled={deletingId === job.id}
                            >
                                {deletingId === job.id ? 'Deleting...' : 'Delete'}
                            </button>
                        </div>
                    </article>
                ))}
            </div>
        </main>
    )
}