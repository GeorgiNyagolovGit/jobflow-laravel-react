import { useState } from 'react'
import profileApi from '../../api/profileApi'
import './SettingsPage.css'

export default function SettingsPage() {
    const [form, setForm] = useState({ current_password: '', password: '', password_confirmation: '' }); const [message, setMessage] = useState(''); const [error, setError] = useState(''); const [submitting, setSubmitting] = useState(false)
    function change(event) { setForm((current) => ({ ...current, [event.target.name]: event.target.value })) }
    async function submit(event) { event.preventDefault(); setSubmitting(true); setError(''); setMessage(''); try { await profileApi.updatePassword(form); setMessage('Password updated.'); setForm({ current_password: '', password: '', password_confirmation: '' }) } catch (requestError) { setError(requestError.response?.data?.message || 'Unable to update password.') } finally { setSubmitting(false) } }
    return <main className="dashboard-page"><h1>Settings</h1><form className="panel-form" onSubmit={submit}>{message && <p className="notice-success">{message}</p>}{error && <p className="notice-error">{error}</p>}<label htmlFor="current-password">Current password</label><input id="current-password" name="current_password" type="password" value={form.current_password} onChange={change} required /><label htmlFor="new-password-setting">New password</label><input id="new-password-setting" name="password" type="password" value={form.password} onChange={change} required /><label htmlFor="confirm-password-setting">Confirm password</label><input id="confirm-password-setting" name="password_confirmation" type="password" value={form.password_confirmation} onChange={change} required /><button disabled={submitting} type="submit">{submitting ? 'Saving…' : 'Update password'}</button></form></main>
}
