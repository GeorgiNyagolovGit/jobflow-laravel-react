import PasswordForm from '../../components/PasswordForm/PasswordForm'
import './ResetPasswordPage.css'

export default function ResetPasswordPage() {
    return (
        <main className="reset-password-page">
            <div className="reset-password-card">
                <h1>Choose a new password</h1>
                <p>Enter your email and choose a new password.</p>
                <PasswordForm mode="reset" />
            </div>
        </main>
    )
}