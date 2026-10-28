import PasswordForm from '../../components/PasswordForm/PasswordForm'
import './ForgotPasswordPage.css'

export default function ForgotPasswordPage() {
    return (
        <main className="forgot-password-page">
            <div className="forgot-password-card">
                <h1>Forgot your password?</h1>
                <p>Enter your email address to request a password reset.</p>
                <PasswordForm mode="forgot" />
            </div>
        </main>
    )
}