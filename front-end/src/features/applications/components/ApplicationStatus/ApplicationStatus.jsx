import './ApplicationStatus.css'

export default function ApplicationStatus({ status = 'pending' }) {
    const label = status.charAt(0).toUpperCase() + status.slice(1)
    return <span className={`application-status status-${status}`}>{label}</span>
}
