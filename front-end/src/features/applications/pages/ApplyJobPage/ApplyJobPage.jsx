import { useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import applicationApi from '../../api/applicationApi'
import './ApplyJobPage.css'

export default function ApplyJobPage() {
    const { id } = useParams()
    const navigate = useNavigate()
    const [coverLetter, setCoverLetter] = useState('')
    const [resume, setResume] = useState(null)
    const [error, setError] = useState('')
    const [submitting, setSubmitting] = useState(false)

    async function submit(event) {
        event.preventDefault()
        setSubmitting(true)
        setError('')
        try {
            const data = new FormData()
            data.append('cover_letter', coverLetter)
            if (resume) data.append('resume', resume)
            await applicationApi.apply(id, data)
            navigate('/applications')
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Unable to submit application.')
        } finally {
            setSubmitting(false)
        }
    }

    return <main className="form-page"><Link to={`/jobs/${id}`}>← Back to job</Link><h1>Apply for this job</h1>
        <form className="panel-form" onSubmit={submit}>{error && <p className="notice-error">{error}</p>}
            <label htmlFor="cover-letter">Cover letter</label><textarea id="cover-letter" rows="9" value={coverLetter} onChange={(e) => setCoverLetter(e.target.value)} required />
            <label htmlFor="resume">Resume</label><input id="resume" type="file" accept=".pdf,.doc,.docx" onChange={(e) => setResume(e.target.files?.[0] || null)} />
            <button disabled={submitting} type="submit">{submitting ? 'Submitting…' : 'Submit application'}</button>
        </form>
    </main>
}
