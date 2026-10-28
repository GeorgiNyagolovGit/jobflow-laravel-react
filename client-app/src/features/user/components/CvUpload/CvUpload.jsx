import { useRef, useState } from 'react'
import profileApi from '../../api/profileApi'
import './CvUpload.css'

const MAX_FILE_SIZE = 5 * 1024 * 1024

export default function CvUpload({ profile = {}, onChange }) {
    const inputRef = useRef(null)
    const [selectedFile, setSelectedFile] = useState(null)
    const [error, setError] = useState('')
    const [message, setMessage] = useState('')
    const [submitting, setSubmitting] = useState(false)

    function selectFile(event) {
        const file = event.target.files?.[0] || null
        setError('')
        setMessage('')

        if (!file) {
            setSelectedFile(null)
            return
        }

        const hasAllowedExtension = /\.(pdf|doc|docx)$/i.test(file.name)
        if (!hasAllowedExtension) {
            setError('Choose a PDF, DOC, or DOCX file.')
            event.target.value = ''
            return
        }

        if (file.size > MAX_FILE_SIZE) {
            setError('The CV must be 5 MB or smaller.')
            event.target.value = ''
            return
        }

        setSelectedFile(file)
    }

    async function upload(event) {
        event.preventDefault()
        if (!selectedFile) {
            setError('Choose a CV before uploading.')
            return
        }

        setSubmitting(true)
        setError('')
        setMessage('')

        try {
            const response = await profileApi.uploadCv(selectedFile)
            onChange({
                has_cv: true,
                cv_original_name: response.cv.name,
            })
            setSelectedFile(null)
            if (inputRef.current) inputRef.current.value = ''
            setMessage('CV uploaded successfully.')
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Unable to upload your CV.')
        } finally {
            setSubmitting(false)
        }
    }

    async function remove() {
        setSubmitting(true)
        setError('')
        setMessage('')

        try {
            await profileApi.deleteCv()
            onChange({ has_cv: false, cv_original_name: null })
            setMessage('CV deleted successfully.')
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Unable to delete your CV.')
        } finally {
            setSubmitting(false)
        }
    }

    return (
        <section className="cv-upload" aria-labelledby="cv-heading">
            <div>
                <h2 id="cv-heading">CV / Resume</h2>
                <p>Upload a PDF, DOC, or DOCX file up to 5 MB.</p>
            </div>

            {error && <p className="notice-error" role="alert">{error}</p>}
            {message && <p className="notice-success" role="status">{message}</p>}

            {profile.has_cv && (
                <div className="cv-upload__current">
                    <span>{profile.cv_original_name || 'Your uploaded CV'}</span>
                    <a href={profileApi.cvDownloadUrl()}>Download</a>
                    <button type="button" className="cv-upload__delete" onClick={remove} disabled={submitting}>
                        Delete
                    </button>
                </div>
            )}

            <form className="cv-upload__form" onSubmit={upload}>
                <label htmlFor="profile-cv">{profile.has_cv ? 'Replace CV' : 'Choose CV'}</label>
                <input
                    ref={inputRef}
                    id="profile-cv"
                    type="file"
                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    onChange={selectFile}
                />
                {selectedFile && <p className="cv-upload__selected">Ready to upload: {selectedFile.name}</p>}
                <button type="submit" disabled={submitting || !selectedFile}>
                    {submitting ? 'Uploading…' : profile.has_cv ? 'Replace CV' : 'Upload CV'}
                </button>
            </form>
        </section>
    )
}
