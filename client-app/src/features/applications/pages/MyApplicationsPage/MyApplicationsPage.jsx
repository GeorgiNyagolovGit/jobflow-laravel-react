import { useEffect, useState } from 'react'
import applicationApi from '../../api/applicationApi'
import ApplicationCard from '../../components/ApplicationCard/ApplicationCard'
import './MyApplicationsPage.css'

export default function MyApplicationsPage() {
    const [applications, setApplications] = useState([])
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')

    useEffect(() => {
        applicationApi.getMine().then((data) => setApplications(data.applications?.data || data.applications || data.data || data)).catch(() => setError('Unable to load applications.')).finally(() => setLoading(false))
    }, [])

    async function withdraw(id) {
        if (!window.confirm('Withdraw this application?')) return
        await applicationApi.withdraw(id)
        setApplications((items) => items.filter((item) => item.id !== id))
    }

    return <main className="dashboard-page"><h1>My applications</h1>{loading && <p>Loading…</p>}{error && <p className="notice-error">{error}</p>}
        <div className="card-list">{applications.map((application) => <ApplicationCard key={application.id} application={application} onWithdraw={withdraw} />)}</div>
        {!loading && !error && !applications.length && <p>You have no applications yet.</p>}
    </main>
}
