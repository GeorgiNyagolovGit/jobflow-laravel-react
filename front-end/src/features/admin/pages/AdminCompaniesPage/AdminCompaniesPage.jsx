import { useEffect, useState } from 'react'
import adminApi from '../../api/adminApi'
import AdminSidebar from '../../components/AdminSidebar/AdminSidebar'
import './AdminCompaniesPage.css'

export default function AdminCompaniesPage() {
    const [companies, setCompanies] = useState([])
    const [error, setError] = useState('')

    useEffect(() => {
        adminApi.getCompanies().then((data) => setCompanies(data.companies?.data || data.companies || data.data || data)).catch(() => setError('Unable to load companies.'))
    }, [])

    async function setStatus(id, status) {
        await adminApi.updateCompany(id, { status })
        setCompanies((items) => items.map((company) => company.id === id ? { ...company, status } : company))
    }

    return <main className="admin-page"><AdminSidebar /><section className="admin-main"><h1>Companies</h1>{error && <p className="notice-error">{error}</p>}
        <div className="admin-list">{companies.map((company) => <article key={company.id}><div><h2>{company.name}</h2><p>{company.status}</p></div><div><button type="button" onClick={() => setStatus(company.id, 'approved')}>Approve</button><button type="button" onClick={() => setStatus(company.id, 'rejected')}>Reject</button></div></article>)}</div>
    </section></main>
}
