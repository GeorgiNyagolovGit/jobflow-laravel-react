import { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import companyApi from '../../api/companyApi'
import CompanyForm from '../../components/CompanyForm/CompanyForm'
import './EditCompanyPage.css'

export default function EditCompanyPage() {
    const { id } = useParams(); const navigate = useNavigate()
    const [company, setCompany] = useState(null); const [submitting, setSubmitting] = useState(false); const [error, setError] = useState('')
    useEffect(() => { companyApi.getById(id).then((data) => setCompany(data.company || data.data || data)).catch(() => setError('Unable to load company.')) }, [id])
    async function submit(data) { setSubmitting(true); try { await companyApi.update(id, data); navigate(`/companies/${id}`) } finally { setSubmitting(false) } }
    if (error) return <p className="notice-error page-message">{error}</p>
    if (!company) return <p className="page-message">Loading company…</p>
    return <main className="form-page"><h1>Edit company</h1><CompanyForm initialData={company} onSubmit={submit} submitting={submitting} /></main>
}
