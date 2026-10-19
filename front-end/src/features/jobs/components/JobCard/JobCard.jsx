import { Link } from 'react-router-dom'
import './JobCard.css'

export default function JobCard({ job }) {
    const company = job.company?.name || job.company_name || 'Company not specified'

    return (
        <article className="job-card">
            <div className="job-card-content">
                <h3>{job.title}</h3>
                <p className="job-company">{company}</p>

                <div className="job-card-details">
                    <span>{job.location || 'Location not specified'}</span>
                    <span>{job.job_type?.name || job.employment_type || 'Employment type not specified'}</span>
                    {(job.salary || job.salary_min) && <span>{job.salary || `${job.salary_min}${job.salary_max ? ` – ${job.salary_max}` : ''} ${job.salary_currency || ''}`}</span>}
                </div>

                <p className="job-description">
                    {job.description?.length > 180
                        ? `${job.description.substring(0, 180)}...`
                        : job.description}
                </p>

                <Link to={`/jobs/${job.id}`} className="job-card-link">
                    View Details
                </Link>
            </div>
        </article>
    )
}
