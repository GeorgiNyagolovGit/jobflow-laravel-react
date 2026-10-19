import { Link } from 'react-router-dom'
import './EmployerDashboardPage.css'

export default function EmployerDashboardPage() {
    return <main className="dashboard-page"><div className="page-heading"><h1>Employer dashboard</h1><p>Manage your company, vacancies and candidates.</p></div><div className="dashboard-grid"><Link className="dashboard-card" to="/my-company"><h2>Company</h2><p>Update your public company profile.</p></Link><Link className="dashboard-card" to="/my-jobs"><h2>Job posts</h2><p>Create and manage job opportunities.</p></Link><Link className="dashboard-card" to="/manage-applications"><h2>Applications</h2><p>Review candidates and update statuses.</p></Link></div></main>
}
