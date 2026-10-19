import { useState } from 'react'
import { Link, NavLink, useNavigate } from 'react-router-dom'
import useAuth from '../../../features/auth/hooks/useAuth'
import './Header.css'

export default function Header() {
    const { user, logout } = useAuth()
    const navigate = useNavigate()
    const [loggingOut, setLoggingOut] = useState(false)
    const [logoutError, setLogoutError] = useState('')

    async function handleLogout() {
        setLoggingOut(true)
        setLogoutError('')

        try {
            await logout()
            navigate('/', { replace: true })
        } catch (error) {
            setLogoutError(
                error.response?.data?.message ||
                    'Logout failed. Please try again.'
            )
        } finally {
            setLoggingOut(false)
        }
    }

    return (
        <header className="site-header">
            <Link className="site-brand" to="/">JobBoard</Link>
            <nav className="site-nav" aria-label="Main navigation">
                <NavLink to="/jobs">Jobs</NavLink>
                {user ? (
                    <>
                        <NavLink to="/dashboard">Dashboard</NavLink>
                        <button
                            type="button"
                            onClick={handleLogout}
                            disabled={loggingOut}
                        >
                            {loggingOut ? 'Logging out…' : 'Logout'}
                        </button>
                        {logoutError && (
                            <span className="site-nav-error" role="alert">
                                {logoutError}
                            </span>
                        )}
                    </>
                ) : (
                    <>
                        <NavLink to="/login">Login</NavLink>
                        <Link className="nav-cta" to="/register">Register</Link>
                    </>
                )}
            </nav>
        </header>
    )
}
