import { useState } from 'react'
import './JobForm.css'

const initialValues = {
    title: '',
    company_id: '',
    location: '',
    employment_type: 'full-time',
    salary: '',
    description: '',
    requirements: '',
}

export default function JobForm({ initialData = {}, onSubmit, submitting = false }) {
    const [form, setForm] = useState({
        ...initialValues,
        ...initialData,
    })

    const [errors, setErrors] = useState({})
    const [submitError, setSubmitError] = useState('')

    function handleChange(event) {
        const { name, value } = event.target

        setForm((current) => ({
            ...current,
            [name]: value,
        }))
    }

    async function handleSubmit(event) {
        event.preventDefault()
        setErrors({})
        setSubmitError('')

        try {
            await onSubmit({
                ...form,
                company_id: form.company_id ? Number(form.company_id) : '',
            })
        } catch (error) {
            const response = error.response?.data

            if (response?.errors) {
                setErrors(response.errors)
            } else {
                setSubmitError(response?.message || 'Unable to save the job.')
            }
        }
    }

    return (
        <form className="job-form" onSubmit={handleSubmit}>
            {submitError && <p className="job-form-error">{submitError}</p>}

            <div className="job-form-field">
                <label htmlFor="title">Job title</label>
                <input
                    id="title"
                    name="title"
                    value={form.title}
                    onChange={handleChange}
                    required
                />
                {errors.title && <p className="job-form-error">{errors.title[0]}</p>}
            </div>

            <div className="job-form-field">
                <label htmlFor="company_id">Company ID</label>
                <input
                    id="company_id"
                    name="company_id"
                    type="number"
                    min="1"
                    value={form.company_id}
                    onChange={handleChange}
                />
                {errors.company_id && (
                    <p className="job-form-error">{errors.company_id[0]}</p>
                )}
            </div>

            <div className="job-form-field">
                <label htmlFor="location">Location</label>
                <input
                    id="location"
                    name="location"
                    value={form.location}
                    onChange={handleChange}
                />
                {errors.location && (
                    <p className="job-form-error">{errors.location[0]}</p>
                )}
            </div>

            <div className="job-form-field">
                <label htmlFor="employment_type">Employment type</label>
                <select
                    id="employment_type"
                    name="employment_type"
                    value={form.employment_type}
                    onChange={handleChange}
                    required
                >
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="contract">Contract</option>
                    <option value="internship">Internship</option>
                    <option value="remote">Remote</option>
                </select>
                {errors.employment_type && (
                    <p className="job-form-error">{errors.employment_type[0]}</p>
                )}
            </div>

            <div className="job-form-field">
                <label htmlFor="salary">Salary</label>
                <input
                    id="salary"
                    name="salary"
                    value={form.salary}
                    onChange={handleChange}
                    placeholder="Optional"
                />
                {errors.salary && (
                    <p className="job-form-error">{errors.salary[0]}</p>
                )}
            </div>

            <div className="job-form-field">
                <label htmlFor="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    value={form.description}
                    onChange={handleChange}
                    required
                />
                {errors.description && (
                    <p className="job-form-error">{errors.description[0]}</p>
                )}
            </div>

            <div className="job-form-field">
                <label htmlFor="requirements">Requirements</label>
                <textarea
                    id="requirements"
                    name="requirements"
                    rows="4"
                    value={form.requirements}
                    onChange={handleChange}
                />
                {errors.requirements && (
                    <p className="job-form-error">{errors.requirements[0]}</p>
                )}
            </div>

            <button type="submit" disabled={submitting}>
                {submitting ? 'Saving...' : 'Save job'}
            </button>
        </form>
    )
}