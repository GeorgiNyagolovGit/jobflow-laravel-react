import { useState } from 'react'
import authApi from '../../api/authApi'
import './VerifyEmailPage.css'

export default function VerifyEmailPage() {
    const [message, setMessage] = useState('')
    const [error, setError] = useState('')
    const [loading, setLoading] = useState(false)

    const handleResend = async () => {
        setMessage('')
        setError('')
        setLoading(true)

        try {
            const data = await authApi.resendVerification()
            setMessage(data.message || 'Verification email sent.')
        } catch (error) {
            setError(
                error.response?.data?.message ||
                'Unable to send the verification email.'
            )
        } finally {
            setLoading(false)
        }
    }

    return (
        <main className="verify-email-page">
            <div className="verify-email-card">
                <h1>Verify your email</h1>

                <p>
                    Please check your inbox for the email verification link.
                </p>

                {message && <p className="verify-success">{message}</p>}
                {error && <p className="verify-error">{error}</p>}

                <button onClick={handleResend} disabled={loading}>
                    {loading ? 'Sending...' : 'Resend verification email'}
                </button>
            </div>
        </main>
    )
}