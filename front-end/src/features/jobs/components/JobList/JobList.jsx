import JobCard from '../JobCard/JobCard'
import './JobList.css'

export default function JobList({ jobs, loading, error }) {
    if (loading) {
        return <p className="job-list-message">Loading jobs...</p>
    }

    if (error) {
        return <p className="job-list-error">{error}</p>
    }

    if (!jobs.length) {
        return <p className="job-list-message">No jobs found.</p>
    }

    return (
        <div className="job-list">
            {jobs.map((job) => (
                <JobCard key={job.id} job={job} />
            ))}
        </div>
    )
}