import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import companyApi from '../../api/companyApi'
import './MyCompanyPage.css'

export default function MyCompanyPage() {
    const [company, setCompany] = useState(null); const [loading, setLoading] = useState(true); const [error, setError] = useState('')
    useEffect(() => { companyApi.getMine().then((data) => setCompany(data.company || data.data || data)).catch((requestError) => { if (requestError.response?.status !== 404) setError('Unable to load your company.') }).finally(() => setLoading(false)) }, [])
    if (loading) return <p>Loading company…</p>
    return <main className="dashboard-page"><h1>My company</h1>{error && <p className="notice-error">{error}</p>}{company ? <article className="dashboard-card"><h2>{company.name}</h2><p>{company.description}</p><Link to={`/companies/${company.id}/edit`}>Edit company</Link></article> : <div className="empty-state"><p>You have not created a company yet.</p><Link className="primary-link" to="/companies/create">Create company</Link></div>}</main>
}
