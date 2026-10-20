import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import useAuth from '../../hooks/useAuth'
import authApi from '../../api/authApi'
import './RegisterForm.css'

export default function RegisterForm() {
    const { register } = useAuth()
    const navigate = useNavigate()

    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    const [error, setError] = useState('')
    const [errors, setErrors] = useState({})
    const [loading, setLoading] = useState(false)

    const handleChange = (event) => {
        setForm({
            ...form,
            [event.target.name]: event.target.value,
        })
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        setError('')
        setErrors({})
        setLoading(true)

        try {
            await register(form)
            navigate('/dashboard')
        } catch (error) {
            setError(error.response?.data?.message || 'Registration failed.')
            setErrors(error.response?.data?.errors || {})
        } finally {
            setLoading(false)
        }
    }

    return (
        <form className="register-form" onSubmit={handleSubmit}>
            <h2>Create an account</h2>

            {error && <p className="auth-error">{error}</p>}

            <label htmlFor="register-name">Full name</label>
            <input
                id="register-name"
                name="name"
                value={form.name}
                onChange={handleChange}
                autoComplete="name"
                required
            />
            {errors.name && <p className="auth-error">{errors.name[0]}</p>}

            <label htmlFor="register-email">Email</label>
            <input
                id="register-email"
                type="email"
                name="email"
                value={form.email}
                onChange={handleChange}
                autoComplete="email"
                required
            />
            {errors.email && <p className="auth-error">{errors.email[0]}</p>}

            <label htmlFor="register-password">Password</label>
            <input
                id="register-password"
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                autoComplete="new-password"
                required
            />
            {errors.password && (
                <p className="auth-error">{errors.password[0]}</p>
            )}

            <label htmlFor="register-confirmation">Confirm password</label>
            <input
                id="register-confirmation"
                type="password"
                name="password_confirmation"
                value={form.password_confirmation}
                onChange={handleChange}
                autoComplete="new-password"
                required
            />

            <button type="submit" disabled={loading}>
                {loading ? 'Creating account...' : 'Register'}
            </button>

            <button type="button" className="google-auth-button" onClick={() => authApi.loginWithGoogle()}>
                Continue with Google
            </button>

            <p>
                Already have an account? <Link to="/login">Sign in</Link>
            </p>
        </form>
    )
}
