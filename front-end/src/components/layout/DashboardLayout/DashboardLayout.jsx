import { Outlet } from 'react-router-dom'
import Sidebar from '../Sidebar/Sidebar'
import './DashboardLayout.css'

export default function DashboardLayout() {
    return (
        <div className="dashboard-layout">
            <Sidebar />
            <div className="dashboard-content"><Outlet /></div>
        </div>
    )
}
