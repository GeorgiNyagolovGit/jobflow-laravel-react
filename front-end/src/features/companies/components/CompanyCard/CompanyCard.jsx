import { Link } from 'react-router-dom'
import './CompanyCard.css'

export default function CompanyCard({ company }) {
    return <article className="company-card">
        <div className="company-logo">{company.name?.charAt(0) || 'C'}</div>
        <div><h2>{company.name}</h2><p>{company.industry || 'Industry not specified'}</p><p>{company.location || 'Location not specified'}</p><Link to={`/companies/${company.id}`}>View company</Link></div>
    </article>
}
