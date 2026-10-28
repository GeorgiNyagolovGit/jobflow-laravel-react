import { Link } from 'react-router-dom'
import './Footer.css'

export default function Footer() {
    return (
        <footer className="site-footer">
            <p>© {new Date().getFullYear()} JobBoard</p>
            <nav aria-label="Footer navigation">
                <Link to="/jobs">Jobs</Link>
            </nav>
        </footer>
    )
}
