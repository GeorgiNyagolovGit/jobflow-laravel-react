import { Navigate, Outlet } from 'react-router-dom'
import useAuth from '../features/auth/hooks/useAuth'

export default function RoleRoute({ roles }) {
    const { user, loading } = useAuth()

    if (loading) {
        return <p>Loading...</p>
    }

    if (!user) {
        return <Navigate to="/login" replace />
    }

    const userRoles = user.roles || []
    const roleNames = userRoles.map((role) => {
        return typeof role === 'string' ? role : role.name
    })

    if (!roles.some((role) => roleNames.includes(role))) {
        return <Navigate to="/" replace />
    }

    return <Outlet />
}