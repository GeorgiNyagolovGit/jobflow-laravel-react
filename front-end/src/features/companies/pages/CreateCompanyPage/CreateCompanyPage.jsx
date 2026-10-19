import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import companyApi from '../../api/companyApi'
import CompanyForm from '../../components/CompanyForm/CompanyForm'
import './CreateCompanyPage.css'

export default function CreateCompanyPage() {
    const [submitting, setSubmitting] = useState(false)
    const navigate = useNavigate()
    async function submit(data) { setSubmitting(true); try { const response = await companyApi.create(data); navigate(`/companies/${response.company?.id || response.data?.id || response.id}`) } finally { setSubmitting(false) } }
    return <main className="form-page"><h1>Create company</h1><CompanyForm onSubmit={submit} submitting={submitting} /></main>
}
