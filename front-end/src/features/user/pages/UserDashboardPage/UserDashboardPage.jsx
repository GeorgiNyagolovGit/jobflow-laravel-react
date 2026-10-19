import { Link } from 'react-router-dom'
import useAuth from '../../../auth/hooks/useAuth'
import './UserDashboardPage.css'

export default function UserDashboardPage() {
    const { user } = useAuth()
    return <main className="dashboard-page"><div className="page-heading"><h1>Welcome, {user?.name}</h1><p>Manage your job search from one place.</p></div><div className="dashboard-grid"><Link className="dashboard-card" to="/applications"><h2>Applications</h2><p>Track your submitted applications.</p></Link><Link className="dashboard-card" to="/favorites"><h2>Favorites</h2><p>Return to jobs you saved.</p></Link><Link className="dashboard-card" to="/profile"><h2>Profile</h2><p>Keep your personal information current.</p></Link></div></main>
}
