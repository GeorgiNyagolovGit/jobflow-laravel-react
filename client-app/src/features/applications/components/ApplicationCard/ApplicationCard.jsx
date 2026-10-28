import { Link } from 'react-router-dom'
import ApplicationStatus from '../ApplicationStatus/ApplicationStatus'
import './ApplicationCard.css'

export default function ApplicationCard({ application, onWithdraw, actions }) {
    const job = application.job || {}
    return (
        <article className="application-card">
            <div>
                <h2>{job.title || application.job_title || 'Job application'}</h2>
                <p>{job.company?.name || application.company_name || 'Company'}</p>
                <ApplicationStatus status={application.status} />
            </div>
            <div className="application-actions">
                {job.id && <Link to={`/jobs/${job.id}`}>View job</Link>}
                {actions}
                {onWithdraw && <button type="button" onClick={() => onWithdraw(application.id)}>Withdraw</button>}
            </div>
        </article>
    )
}
