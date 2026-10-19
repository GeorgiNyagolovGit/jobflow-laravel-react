import { useMemo, useState } from 'react'
import CompanyCard from '../../components/CompanyCard/CompanyCard'
import useCompanies from '../../hooks/useCompanies'
import './CompaniesPage.css'

export default function CompaniesPage() {
    const [search, setSearch] = useState('')
    const params = useMemo(() => ({}), [])
    const { companies, loading, error } = useCompanies(params)
    const visible = companies.filter((company) => company.name?.toLowerCase().includes(search.toLowerCase()))

    return <main className="listing-page"><div className="page-heading"><h1>Companies</h1><p>Discover teams that are hiring.</p></div>
        <input className="listing-search" type="search" placeholder="Search companies" value={search} onChange={(event) => setSearch(event.target.value)} />
        {loading && <p>Loading companies…</p>}{error && <p className="notice-error">{error}</p>}
        <div className="company-grid">{visible.map((company) => <CompanyCard key={company.id} company={company} />)}</div>
    </main>
}
