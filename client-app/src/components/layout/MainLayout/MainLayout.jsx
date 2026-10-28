import { Outlet } from 'react-router-dom'
import Footer from '../Footer/Footer'
import Header from '../Header/Header'
import './MainLayout.css'

export default function MainLayout() {
    return (
        <div className="app-shell">
            <Header />
            <div className="app-content"><Outlet /></div>
            <Footer />
        </div>
    )
}
