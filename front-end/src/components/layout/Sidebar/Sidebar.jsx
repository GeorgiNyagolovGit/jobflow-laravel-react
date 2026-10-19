import { NavLink } from 'react-router-dom'
import useAuth from '../../../features/auth/hooks/useAuth'
import './Sidebar.css'

export default function Sidebar() {
    const { user } = useAuth()
    const roles = (user?.roles || []).map((role) => typeof role === 'string' ? role : role.name)

    return (
        <aside className="dashboard-sidebar">
            <p className="sidebar-user">{user?.name}</p>
            <NavLink to="/dashboard">Overview</NavLink>
            <NavLink to="/profile">Profile</NavLink>
            <NavLink to="/settings">Settings</NavLink>
            <NavLink to="/applications">Applications</NavLink>
            <NavLink to="/favorites">Favorites</NavLink>
            {roles.includes('employer') && (
                <>
                    <NavLink to="/employer">Employer</NavLink>
                    <NavLink to="/my-company">My company</NavLink>
                    <NavLink to="/my-jobs">My jobs</NavLink>
                </>
            )}
            {roles.includes('admin') && <NavLink to="/admin">Administration</NavLink>}
        </aside>
    )
}
