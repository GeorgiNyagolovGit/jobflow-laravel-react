import { useEffect, useState } from 'react'
import './CompanyForm.css'

const emptyCompany = { name: '', website: '', industry: '', location: '', description: '' }

export default function CompanyForm({ initialData = {}, onSubmit, submitting = false }) {
    const [form, setForm] = useState({ ...emptyCompany, ...initialData })
    const [errors, setErrors] = useState({})
    const [error, setError] = useState('')

    useEffect(() => setForm({ ...emptyCompany, ...initialData }), [initialData])

    function change(event) {
        setForm((current) => ({ ...current, [event.target.name]: event.target.value }))
    }

    async function submit(event) {
        event.preventDefault()
        setErrors({})
        setError('')
        try { await onSubmit(form) } catch (requestError) {
            setErrors(requestError.response?.data?.errors || {})
            setError(requestError.response?.data?.message || 'Unable to save company.')
        }
    }

    return <form className="panel-form" onSubmit={submit}>{error && <p className="notice-error">{error}</p>}
        <label htmlFor="company-name">Name</label><input id="company-name" name="name" value={form.name} onChange={change} required />{errors.name && <p className="field-error">{errors.name[0]}</p>}
        <label htmlFor="company-industry">Industry</label><input id="company-industry" name="industry" value={form.industry} onChange={change} />
        <label htmlFor="company-location">Location</label><input id="company-location" name="location" value={form.location} onChange={change} />
        <label htmlFor="company-website">Website</label><input id="company-website" name="website" type="url" value={form.website} onChange={change} />
        <label htmlFor="company-description">Description</label><textarea id="company-description" name="description" rows="7" value={form.description} onChange={change} />
        <button type="submit" disabled={submitting}>{submitting ? 'Saving…' : 'Save company'}</button>
    </form>
}
