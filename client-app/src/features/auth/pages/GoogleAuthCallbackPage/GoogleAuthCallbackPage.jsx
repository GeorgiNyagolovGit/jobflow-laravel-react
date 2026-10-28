import { useEffect, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import authApi from '../../api/authApi'
import useAuth from '../../hooks/useAuth'

export default function GoogleAuthCallbackPage() {
    const { setUser } = useAuth()
    const navigate = useNavigate()
    const [error, setError] = useState('')

    useEffect(() => {
        authApi.getUser()
            .then((data) => {
                setUser(data.user || data)
                navigate('/dashboard', { replace: true })
            })
            .catch(() => {
                setError('Google sign-in could not be completed. Please try again.')
            })
    }, [navigate, setUser])

    if (error) {
        return (
            <main className="auth-page">
                <div className="auth-card">
                    <h1>Sign-in failed</h1>
                    <p className="auth-error">{error}</p>
                    <Link to="/login">Back to sign in</Link>
                </div>
            </main>
        )
    }

    return (
        <main className="auth-page">
            <div className="auth-card">
                <h1>Signing you in…</h1>
                <p>We are securely completing your Google sign-in.</p>
            </div>
        </main>
    )
}
