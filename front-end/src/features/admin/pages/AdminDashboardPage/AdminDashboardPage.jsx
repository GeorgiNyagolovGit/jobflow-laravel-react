import { useEffect, useState } from 'react'
import adminApi from '../../api/adminApi'
import AdminSidebar from '../../components/AdminSidebar/AdminSidebar'
import AdminStats from '../../components/AdminStats/AdminStats'
import './AdminDashboardPage.css'

export default function AdminDashboardPage() {
    const [stats, setStats] = useState({})
    const [error, setError] = useState('')

    useEffect(() => {
        adminApi.getStats().then((data) => setStats(data.stats || data)).catch(() => {
            setError('Unable to load administration statistics.')
        })
    }, [])

    return (
        <main className="admin-page">
            <AdminSidebar />
            <section className="admin-main">
                <h1>Administration</h1>
                {error ? <p className="notice-error">{error}</p> : <AdminStats stats={stats} />}
            </section>
        </main>
    )
}
