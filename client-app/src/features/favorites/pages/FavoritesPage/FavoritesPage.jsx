import { useEffect, useState } from 'react'
import favoriteApi from '../../api/favoriteApi'
import JobCard from '../../../jobs/components/JobCard/JobCard'
import './FavoritesPage.css'

export default function FavoritesPage() {
    const [jobs, setJobs] = useState([]); const [loading, setLoading] = useState(true); const [error, setError] = useState('')
    useEffect(() => { favoriteApi.getAll().then((data) => setJobs(data.jobs?.data || data.jobs || data.favorites || data.data || data)).catch(() => setError('Unable to load favorites.')).finally(() => setLoading(false)) }, [])
    return <main className="dashboard-page"><h1>Favorite jobs</h1>{loading && <p>Loading…</p>}{error && <p className="notice-error">{error}</p>}<div className="job-list">{jobs.map((job) => <JobCard key={job.id} job={job.job || job} />)}</div>{!loading && !error && !jobs.length && <p>No favorite jobs yet.</p>}</main>
}
