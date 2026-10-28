import { useEffect, useState } from 'react'
import './ProfileForm.css'

export default function ProfileForm({ user = {}, onSubmit, submitting = false }) {
    const [form, setForm] = useState({
        name: '',
        email: '',
        phone: '',
        bio: '',
        ...user,
    })
    const [error, setError] = useState('')

    useEffect(() => {
        setForm({
            name: '',
            email: '',
            phone: '',
            bio: '',
            ...user,
        })
    }, [user])

    function change(event) {
        setForm((current) => ({
            ...current,
            [event.target.name]: event.target.value,
        }))
    }

    async function submit(event) {
        event.preventDefault()
        setError('')

        try {
            await onSubmit(form)
        } catch (requestError) {
            setError(
                requestError.response?.data?.message ||
                    'Unable to update profile.',
            )
        }
    }

    return (
        <form className="panel-form" onSubmit={submit}>
            {error && <p className="notice-error">{error}</p>}

            <label htmlFor="profile-name">Name</label>
            <input
                id="profile-name"
                name="name"
                value={form.name}
                onChange={change}
                required
            />

            <label htmlFor="profile-email">Email</label>
            <input
                id="profile-email"
                name="email"
                type="email"
                value={form.email}
                onChange={change}
                required
            />

            <label htmlFor="profile-phone">Phone</label>
            <input
                id="profile-phone"
                name="phone"
                value={form.phone || ''}
                onChange={change}
            />

            <label htmlFor="profile-bio">Bio</label>
            <textarea
                id="profile-bio"
                name="bio"
                rows="6"
                value={form.bio || ''}
                onChange={change}
            />

            <button type="submit" disabled={submitting}>
                {submitting ? 'Saving…' : 'Save profile'}
            </button>
        </form>
    )
}
