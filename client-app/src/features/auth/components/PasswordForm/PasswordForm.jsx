import { useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import authApi from '../../api/authApi'
import './PasswordForm.css'

export default function PasswordForm({ mode = 'forgot' }) {
    const [searchParams] = useSearchParams()
    const [email, setEmail] = useState(searchParams.get('email') || '')
    const [password, setPassword] = useState('')
    const [confirmation, setConfirmation] = useState('')
    const [message, setMessage] = useState('')
    const [error, setError] = useState('')
    const [loading, setLoading] = useState(false)

    const isReset = mode === 'reset'

    const handleSubmit = async (event) => {
        event.preventDefault()
        setMessage('')
        setError('')
        setLoading(true)

        try {
            if (isReset) {
                await authApi.resetPassword({
                    token: searchParams.get('token'),
                    email,
                    password,
                    password_confirmation: confirmation,
                })

                setMessage('Your password has been reset successfully.')
            } else {
                await authApi.forgotPassword({ email })

                setMessage('If the email exists, a reset link will be sent.')
            }
        } catch (error) {
            setError(
                error.response?.data?.message ||
                'The request could not be completed.'
            )
        } finally {
            setLoading(false)
        }
    }

    return (
        <form className="password-form" onSubmit={handleSubmit}>
            <h2>{isReset ? 'Reset password' : 'Forgot password'}</h2>

            {message && <p className="password-success">{message}</p>}
            {error && <p className="auth-error">{error}</p>}

            <label htmlFor="password-email">Email</label>
            <input
                id="password-email"
                type="email"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                autoComplete="email"
                required
            />

            {isReset && (
                <>
                    <label htmlFor="new-password">New password</label>
                    <input
                        id="new-password"
                        type="password"
                        value={password}
                        onChange={(event) => setPassword(event.target.value)}
                        autoComplete="new-password"
                        required
                    />

                    <label htmlFor="confirm-password">Confirm new password</label>
                    <input
                        id="confirm-password"
                        type="password"
                        value={confirmation}
                        onChange={(event) => setConfirmation(event.target.value)}
                        autoComplete="new-password"
                        required
                    />
                </>
            )}

            <button type="submit" disabled={loading}>
                {loading ? 'Please wait...' : isReset ? 'Reset password' : 'Send reset link'}
            </button>

            <Link to="/login">Back to sign in</Link>
        </form>
    )
}