import { BrowserRouter, Link, Navigate, Route, Routes } from 'react-router-dom'

import DashboardLayout from '../components/layout/DashboardLayout/DashboardLayout'
import MainLayout from '../components/layout/MainLayout/MainLayout'
import AdminCompaniesPage from '../features/admin/pages/AdminCompaniesPage/AdminCompaniesPage'
import AdminDashboardPage from '../features/admin/pages/AdminDashboardPage/AdminDashboardPage'
import AdminJobsPage from '../features/admin/pages/AdminJobsPage/AdminJobsPage'
import AdminUsersPage from '../features/admin/pages/AdminUsersPage/AdminUsersPage'
import ApplyJobPage from '../features/applications/pages/ApplyJobPage/ApplyJobPage'
import ManageApplicationsPage from '../features/applications/pages/ManageApplicationsPage/ManageApplicationsPage'
import MyApplicationsPage from '../features/applications/pages/MyApplicationsPage/MyApplicationsPage'
import ForgotPasswordPage from '../features/auth/pages/ForgotPasswordPage/ForgotPasswordPage'
import LoginPage from '../features/auth/pages/LoginPage/LoginPage'
import RegisterPage from '../features/auth/pages/RegisterPage/RegisterPage'
import ResetPasswordPage from '../features/auth/pages/ResetPasswordPage/ResetPasswordPage'
import VerifyEmailPage from '../features/auth/pages/VerifyEmailPage/VerifyEmailPage'
import CompaniesPage from '../features/companies/pages/CompaniesPage/CompaniesPage'
import CompanyDetailsPage from '../features/companies/pages/CompanyDetailsPage/CompanyDetailsPage'
import CreateCompanyPage from '../features/companies/pages/CreateCompanyPage/CreateCompanyPage'
import EditCompanyPage from '../features/companies/pages/EditCompanyPage/EditCompanyPage'
import MyCompanyPage from '../features/companies/pages/MyCompanyPage/MyCompanyPage'
import EmployerDashboardPage from '../features/employer/pages/EmployerDashboardPage/EmployerDashboardPage'
import FavoritesPage from '../features/favorites/pages/FavoritesPage/FavoritesPage'
import CreateJobPage from '../features/jobs/pages/CreateJobPage/CreateJobPage'
import EditJobPage from '../features/jobs/pages/EditJobPage/EditJobPage'
import JobDetailsPage from '../features/jobs/pages/JobDetailsPage/JobDetailsPage'
import JobsPage from '../features/jobs/pages/JobsPage/JobsPage'
import MyJobsPage from '../features/jobs/pages/MyJobsPage/MyJobsPage'
import ProfilePage from '../features/user/pages/ProfilePage/ProfilePage'
import SettingsPage from '../features/user/pages/SettingsPage/SettingsPage'
import UserDashboardPage from '../features/user/pages/UserDashboardPage/UserDashboardPage'
import ProtectedRoute from './ProtectedRoute'
import RoleRoute from './RoleRoute'

function HomePage() {
    return (
        <main className="home-page">
            <section className="home-hero">
                <p className="eyebrow">Build your next chapter</p>
                <h1>Find work that fits your ambition.</h1>
                <p>Explore opportunities and manage every application in one place.</p>
                <div className="hero-actions"><Link className="primary-link" to="/jobs">Browse jobs</Link></div>
            </section>
            <section className="home-features"><article><strong>Search</strong><p>Filter current roles by keyword and location.</p></article><article><strong>Apply</strong><p>Keep your applications organized.</p></article><article><strong>Hire</strong><p>Publish roles and review candidates.</p></article></section>
        </main>
    )
}

function NotFoundPage() {
    return <main className="empty-state page-message"><h1>Page not found</h1><p>The page you requested does not exist.</p><Link to="/">Go home</Link></main>
}

export default function AppRouter() {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<MainLayout />}>
                    <Route index element={<HomePage />} />
                    <Route path="jobs" element={<JobsPage />} />
                    <Route path="jobs/:id" element={<JobDetailsPage />} />
                    <Route path="companies" element={<CompaniesPage />} />
                    <Route path="companies/:id" element={<CompanyDetailsPage />} />
                    <Route path="login" element={<LoginPage />} />
                    <Route path="register" element={<RegisterPage />} />
                    <Route path="forgot-password" element={<ForgotPasswordPage />} />
                    <Route path="reset-password" element={<ResetPasswordPage />} />
                    <Route path="verify-email" element={<VerifyEmailPage />} />

                    <Route element={<ProtectedRoute />}>
                        <Route path="jobs/:id/apply" element={<ApplyJobPage />} />
                        <Route element={<DashboardLayout />}>
                            <Route path="dashboard" element={<UserDashboardPage />} />
                            <Route path="profile" element={<ProfilePage />} />
                            <Route path="settings" element={<SettingsPage />} />
                            <Route path="applications" element={<MyApplicationsPage />} />
                            <Route path="favorites" element={<FavoritesPage />} />

                            <Route element={<RoleRoute roles={['employer', 'admin']} />}>
                                <Route path="employer" element={<EmployerDashboardPage />} />
                                <Route path="my-company" element={<MyCompanyPage />} />
                                <Route path="companies/create" element={<CreateCompanyPage />} />
                                <Route path="companies/:id/edit" element={<EditCompanyPage />} />
                                <Route path="jobs/create" element={<CreateJobPage />} />
                                <Route path="jobs/:id/edit" element={<EditJobPage />} />
                                <Route path="my-jobs" element={<MyJobsPage />} />
                                <Route path="manage-applications" element={<ManageApplicationsPage />} />
                            </Route>

                            <Route element={<RoleRoute roles={['admin']} />}>
                                <Route path="admin" element={<AdminDashboardPage />} />
                                <Route path="admin/users" element={<AdminUsersPage />} />
                                <Route path="admin/jobs" element={<AdminJobsPage />} />
                                <Route path="admin/companies" element={<AdminCompaniesPage />} />
                            </Route>
                        </Route>
                    </Route>

                    <Route path="404" element={<NotFoundPage />} />
                    <Route path="*" element={<Navigate to="/404" replace />} />
                </Route>
            </Routes>
        </BrowserRouter>
    )
}
