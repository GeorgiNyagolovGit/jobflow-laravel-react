import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import companyApi from '../../api/companyApi'
import './CompanyDetailsPage.css'

export default function CompanyDetailsPage() {
    const { id } = useParams()
    const [company, setCompany] = useState(null)
    const [error, setError] = useState('')

    useEffect(() => {
        companyApi.getById(id).then((data) => setCompany(data.company || data.data || data)).catch(() => setError('Unable to load company.'))
    }, [id])

    if (error) return <p className="notice-error page-message">{error}</p>
    if (!company) return <p className="page-message">Loading company…</p>

    return <main className="details-page"><Link to="/companies">← Companies</Link><div className="details-hero"><div className="company-logo large">{company.name?.charAt(0)}</div><div><h1>{company.name}</h1><p>{company.industry} · {company.location}</p></div></div>
        <section className="details-section"><h2>About</h2><p>{company.description || 'No description available.'}</p>{company.website && <a href={company.website} target="_blank" rel="noreferrer">Visit website</a>}</section>
    </main>
}
