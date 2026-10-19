import RegisterForm from '../../components/RegisterForm/RegisterForm'
import './RegisterPage.css'

export default function RegisterPage() {
    return (
        <main className="register-page">
            <div className="register-card">
                <h1>Join our job platform</h1>
                <p>Create your account to get started.</p>
                <RegisterForm />
            </div>
        </main>
    )
}