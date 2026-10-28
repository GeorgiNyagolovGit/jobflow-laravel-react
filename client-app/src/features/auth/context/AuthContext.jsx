import { createContext, useEffect, useState } from 'react'
import authApi from '../api/authApi'

export const AuthContext = createContext(null)

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null)
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        authApi.getUser()
            .then((data) => {
                setUser(data.user || data)
            })
            .catch(() => {
                setUser(null)
            })
            .finally(() => {
                setLoading(false)
            })
    }, [])

    const login = async (credentials) => {
        const data = await authApi.login(credentials)
        setUser(data.user)
        return data
    }

    const register = async (formData) => {
        const data = await authApi.register(formData)
        setUser(data.user)
        return data
    }

    const logout = async () => {
        try {
            await authApi.logout()
            setUser(null)
        } catch (error) {
            if (error.response?.status === 401) {
                setUser(null)
                return
            }

            throw error
        }
    }

    const value = {
        user,
        loading,
        isAuthenticated: Boolean(user),
        login,
        register,
        logout,
        setUser,
    }

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    )
}
