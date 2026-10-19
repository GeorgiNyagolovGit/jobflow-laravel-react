import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import useAuth from '../../hooks/useAuth'
import './LoginForm.css'

export default function LoginForm() {
    const { login } = useAuth()
    const navigate = useNavigate()

    const [form, setForm] = useState({
        email: '',
        password: '',
        remember: false,
    })

    const [error, setError] = useState('')
    const [loading, setLoading] = useState(false)

    const handleChange = (event) => {
        const { name, value, checked, type } = event.target

        setForm({
            ...form,
            [name]: type === 'checkbox' ? checked : value,
        })
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        setError('')
        setLoading(true)

        try {
            await login(form)
            navigate('/dashboard')
        } catch (error) {
            setError(
                error.response?.data?.message ||
                'Unable to log in. Please check your details.'
            )
        } finally {
            setLoading(false)
        }
    }

    return (
        <form className="login-form" onSubmit={handleSubmit}>
            <h2>Sign in</h2>

            {error && <p className="auth-error">{error}</p>}

            <label htmlFor="login-email">Email</label>
            <input
                id="login-email"
                type="email"
                name="email"
                value={form.email}
                onChange={handleChange}
                autoComplete="email"
                required
            />

            <label htmlFor="login-password">Password</label>
            <input
                id="login-password"
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                autoComplete="current-password"
                required
            />

            <label className="login-remember">
                <input
                    type="checkbox"
                    name="remember"
                    checked={form.remember}
                    onChange={handleChange}
                />
                Remember me
            </label>

            <button type="submit" disabled={loading}>
                {loading ? 'Signing in...' : 'Sign in'}
            </button>

            <Link to="/forgot-password">Forgot password?</Link>
            <p>
                Don't have an account? <Link to="/register">Register</Link>
            </p>
        </form>
    )
}