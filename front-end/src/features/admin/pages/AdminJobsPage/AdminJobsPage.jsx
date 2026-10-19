import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import adminApi from '../../api/adminApi'
import AdminSidebar from '../../components/AdminSidebar/AdminSidebar'
import './AdminJobsPage.css'

export default function AdminJobsPage() {
    const [jobs, setJobs] = useState([])
    const [error, setError] = useState('')

    useEffect(() => {
        adminApi.getJobs().then((data) => setJobs(data.jobs?.data || data.jobs || data.data || data)).catch(() => setError('Unable to load jobs.'))
    }, [])

    async function removeJob(id) {
        if (!window.confirm('Delete this job?')) return
        await adminApi.deleteJob(id)
        setJobs((items) => items.filter((job) => job.id !== id))
    }

    return <main className="admin-page"><AdminSidebar /><section className="admin-main"><h1>Jobs</h1>{error && <p className="notice-error">{error}</p>}
        <div className="admin-list">{jobs.map((job) => <article key={job.id}><div><h2>{job.title}</h2><p>{job.company?.name || 'No company'} · {job.status}</p></div><div><Link to={`/jobs/${job.id}`}>View</Link><button type="button" onClick={() => removeJob(job.id)}>Delete</button></div></article>)}</div>
    </section></main>
}
