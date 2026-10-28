import { AuthProvider } from './features/auth/context/AuthContext'
import AppRouter from './routes/AppRouter'
import './App.css'

export default function App() {
  return (
    <AuthProvider>
      <AppRouter />
    </AuthProvider>
  )
}
