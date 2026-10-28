import { useCallback, useEffect, useState } from 'react'
import jobApi from '../../api/jobApi'
import JobList from '../../components/JobList/JobList'
import JobFilters from '../../components/JobFilters/JobFilters'
import JobSearch from '../../components/JobSearch/JobSearch'
import './JobsPage.css'

const initialFilters = {
    location: '',
    category_id: '',
    job_type_id: '',
    is_remote: '',
    salary_min: '',
    salary_max: '',
    sort: 'latest',
    per_page: '12',
}

export default function JobsPage() {
    const [jobs, setJobs] = useState([])
    const [search, setSearch] = useState('')
    const [submittedSearch, setSubmittedSearch] = useState('')
    const [filters, setFilters] = useState(initialFilters)
    const [appliedFilters, setAppliedFilters] = useState(initialFilters)
    const [filterOptions, setFilterOptions] = useState({
        categories: [],
        jobTypes: [],
    })
    const [pagination, setPagination] = useState({
        currentPage: 1,
        lastPage: 1,
        total: 0,
        from: 0,
        to: 0,
    })
    const [currentPage, setCurrentPage] = useState(1)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState('')

    const loadJobs = useCallback(async () => {
        setLoading(true)
        setError('')

        try {
            const params = {
                ...appliedFilters,
                search: submittedSearch,
                page: currentPage,
            }

            Object.keys(params).forEach((key) => {
                if (
                    params[key] === '' ||
                    params[key] === null ||
                    params[key] === undefined
                ) {
                    delete params[key]
                }
            })

            const data = await jobApi.getAll(params)
            const jobsPayload = data.jobs || data
            const jobItems = jobsPayload.data || data.data || jobsPayload

            setJobs(Array.isArray(jobItems) ? jobItems : [])
            setFilterOptions({
                categories: data.categories || [],
                jobTypes: data.jobTypes || [],
            })
            setPagination({
                currentPage: jobsPayload.current_page || 1,
                lastPage: jobsPayload.last_page || 1,
                total: jobsPayload.total ?? jobItems.length ?? 0,
                from: jobsPayload.from || (jobItems.length ? 1 : 0),
                to: jobsPayload.to || jobItems.length || 0,
            })
        } catch (requestError) {
            setError(
                requestError.response?.data?.message || 'Unable to load jobs.'
            )
        } finally {
            setLoading(false)
        }
    }, [appliedFilters, currentPage, submittedSearch])

    useEffect(() => {
        loadJobs()
    }, [loadJobs])

    function applyFilters() {
        setAppliedFilters({ ...filters })
        setCurrentPage(1)
    }

    function submitSearch() {
        setSubmittedSearch(search.trim())
        setCurrentPage(1)
    }

    function resetFilters() {
        setFilters(initialFilters)
        setAppliedFilters(initialFilters)
        setSearch('')
        setSubmittedSearch('')
        setCurrentPage(1)
    }

    return (
        <main className="jobs-page">
            <div className="jobs-page-header">
                <span className="jobs-page-eyebrow">Job opportunities</span>
                <h1>Find work that moves you forward</h1>
                <p>
                    Search open positions by profession, location, work model,
                    and salary.
                </p>
            </div>

            <JobSearch
                value={search}
                onChange={setSearch}
                onSearch={submitSearch}
                loading={loading}
            />

            <div className="jobs-page-layout">
                <JobFilters
                    filters={filters}
                    categories={filterOptions.categories}
                    jobTypes={filterOptions.jobTypes}
                    loading={loading}
                    onChange={setFilters}
                    onApply={applyFilters}
                    onReset={resetFilters}
                />

                <section className="jobs-results" aria-live="polite">
                    <div className="jobs-results-header">
                        <div>
                            <span className="jobs-results-kicker">
                                Available positions
                            </span>
                            <h2>
                                {pagination.total.toLocaleString()} jobs found
                            </h2>
                        </div>

                        {pagination.total > 0 && (
                            <p>
                                Showing {pagination.from}–{pagination.to} of{' '}
                                {pagination.total}
                            </p>
                        )}
                    </div>

                    <JobList jobs={jobs} loading={loading} error={error} />

                    {pagination.lastPage > 1 && !error && (
                        <nav
                            className="jobs-pagination"
                            aria-label="Jobs pagination"
                        >
                            <button
                                type="button"
                                disabled={loading || currentPage === 1}
                                onClick={() =>
                                    setCurrentPage((page) => page - 1)
                                }
                            >
                                Previous
                            </button>
                            <span>
                                Page {pagination.currentPage} of{' '}
                                {pagination.lastPage}
                            </span>
                            <button
                                type="button"
                                disabled={
                                    loading ||
                                    currentPage === pagination.lastPage
                                }
                                onClick={() =>
                                    setCurrentPage((page) => page + 1)
                                }
                            >
                                Next
                            </button>
                        </nav>
                    )}
                </section>
            </div>
        </main>
    )
}
