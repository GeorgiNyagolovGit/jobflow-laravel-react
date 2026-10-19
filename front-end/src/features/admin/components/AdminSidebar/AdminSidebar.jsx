import { NavLink } from 'react-router-dom'
import './AdminSidebar.css'

export default function AdminSidebar() {
    return (
        <aside className="admin-sidebar">
            <h2>Admin</h2>
            <NavLink end to="/admin">Overview</NavLink>
            <NavLink to="/admin/users">Users</NavLink>
            <NavLink to="/admin/jobs">Jobs</NavLink>
            <NavLink to="/admin/companies">Companies</NavLink>
        </aside>
    )
}
