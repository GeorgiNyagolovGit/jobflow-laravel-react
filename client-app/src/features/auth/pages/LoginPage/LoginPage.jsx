import LoginForm from '../../components/LoginForm/LoginForm'
import './LoginPage.css'

export default function LoginPage() {
    return (
        <main className="auth-page">
            <div className="auth-card">
                <h1>Welcome back</h1>
                <p>Sign in to your account to continue.</p>
                <LoginForm />
            </div>
        </main>
    )
}