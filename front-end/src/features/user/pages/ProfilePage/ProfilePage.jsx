import { useEffect, useState } from 'react'
import useAuth from '../../../auth/hooks/useAuth'
import profileApi from '../../api/profileApi'
import ProfileForm from '../../components/ProfileForm/ProfileForm'
import './ProfilePage.css'

export default function ProfilePage() {
    const { user, setUser } = useAuth(); const [profile, setProfile] = useState(user); const [submitting, setSubmitting] = useState(false); const [message, setMessage] = useState('')
    useEffect(() => { profileApi.get().then((data) => setProfile(data.user || data.profile || data)).catch(() => {}) }, [])
    async function submit(data) { setSubmitting(true); setMessage(''); try { const response = await profileApi.update(data); const updated = response.user || response.data || data; setProfile(updated); setUser((current) => ({ ...current, ...updated })); setMessage('Profile updated.') } finally { setSubmitting(false) } }
    return <main className="dashboard-page"><h1>Profile</h1>{message && <p className="notice-success">{message}</p>}<ProfileForm user={profile || {}} onSubmit={submit} submitting={submitting} /></main>
}
