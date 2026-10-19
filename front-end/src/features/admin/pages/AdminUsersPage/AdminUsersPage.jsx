import { useEffect, useState } from 'react'
import adminApi from '../../api/adminApi'
import AdminSidebar from '../../components/AdminSidebar/AdminSidebar'
import './AdminUsersPage.css'

export default function AdminUsersPage() {
    const [users, setUsers] = useState([])
    const [error, setError] = useState('')

    useEffect(() => {
        adminApi.getUsers().then((data) => setUsers(data.users?.data || data.users || data.data || data)).catch(() => {
            setError('Unable to load users.')
        })
    }, [])

    async function removeUser(id) {
        if (!window.confirm('Delete this user?')) return
        await adminApi.deleteUser(id)
        setUsers((items) => items.filter((user) => user.id !== id))
    }

    return (
        <main className="admin-page">
            <AdminSidebar />
            <section className="admin-main">
                <h1>Users</h1>
                {error && <p className="notice-error">{error}</p>}
                <div className="admin-table-wrap"><table className="admin-table"><thead><tr><th>Name</th><th>Email</th><th>Status</th><th /></tr></thead>
                    <tbody>{users.map((user) => <tr key={user.id}><td>{user.name}</td><td>{user.email}</td><td>{user.is_active ? 'Active' : 'Inactive'}</td><td><button onClick={() => removeUser(user.id)} type="button">Delete</button></td></tr>)}</tbody>
                </table></div>
            </section>
        </main>
    )
}
