import { useEffect, useState } from 'react'
import applicationApi from '../../api/applicationApi'
import ApplicationCard from '../../components/ApplicationCard/ApplicationCard'
import './ManageApplicationsPage.css'

export default function ManageApplicationsPage() {
    const [applications, setApplications] = useState([])
    const [error, setError] = useState('')

    useEffect(() => {
        applicationApi.getManaged().then((data) => setApplications(data.applications?.data || data.applications || data.data || data)).catch(() => setError('Unable to load applications.'))
    }, [])

    async function updateStatus(id, status) {
        await applicationApi.updateStatus(id, status)
        setApplications((items) => items.map((item) => item.id === id ? { ...item, status } : item))
    }

    return <main className="dashboard-page"><h1>Manage applications</h1>{error && <p className="notice-error">{error}</p>}
        <div className="card-list">{applications.map((application) => <ApplicationCard key={application.id} application={application} actions={<><button type="button" onClick={() => updateStatus(application.id, 'approved')}>Approve</button><button type="button" onClick={() => updateStatus(application.id, 'rejected')}>Reject</button></>} />)}</div>
    </main>
}
