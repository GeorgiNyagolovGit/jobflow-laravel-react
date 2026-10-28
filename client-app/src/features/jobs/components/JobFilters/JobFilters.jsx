import './JobFilters.css'

const popularLocations = ['Sofia', 'Plovdiv', 'Varna', 'Burgas']

export default function JobFilters({
    filters,
    categories,
    jobTypes,
    loading,
    onChange,
    onApply,
    onReset,
}) {
    function handleChange(event) {
        onChange({
            ...filters,
            [event.target.name]: event.target.value,
        })
    }

    function handleSubmit(event) {
        event.preventDefault()
        onApply()
    }

    const activeFiltersCount = [
        filters.location,
        filters.category_id,
        filters.job_type_id,
        filters.is_remote,
        filters.salary_min,
        filters.salary_max,
    ].filter((value) => value !== '').length

    return (
        <form className="job-filters" onSubmit={handleSubmit}>
            <div className="job-filters-heading">
                <div>
                    <span>Search filters</span>
                    <h2>Refine results</h2>
                </div>
                {activeFiltersCount > 0 && (
                    <span className="job-filters-count">
                        {activeFiltersCount}
                    </span>
                )}
            </div>

            <div className="job-filter-field">
                <label htmlFor="category_id">Category</label>
                <select
                    id="category_id"
                    name="category_id"
                    value={filters.category_id}
                    onChange={handleChange}
                >
                    <option value="">All categories</option>
                    {categories.map((category) => (
                        <option key={category.id} value={category.id}>
                            {category.name}
                        </option>
                    ))}
                </select>
            </div>

            <div className="job-filter-field">
                <label htmlFor="location">Location</label>
                <input
                    id="location"
                    name="location"
                    value={filters.location}
                    onChange={handleChange}
                    placeholder="City or region"
                />
                <div className="job-filter-quick-options">
                    {popularLocations.map((location) => (
                        <button
                            key={location}
                            type="button"
                            className={
                                filters.location === location ? 'is-active' : ''
                            }
                            onClick={() => onChange({ ...filters, location })}
                        >
                            {location}
                        </button>
                    ))}
                </div>
            </div>

            <div className="job-filter-field">
                <label htmlFor="job_type_id">Employment type</label>
                <select
                    id="job_type_id"
                    name="job_type_id"
                    value={filters.job_type_id}
                    onChange={handleChange}
                >
                    <option value="">All employment types</option>
                    {jobTypes.map((jobType) => (
                        <option key={jobType.id} value={jobType.id}>
                            {jobType.name}
                        </option>
                    ))}
                </select>
            </div>

            <div className="job-filter-field">
                <label htmlFor="is_remote">Workplace</label>
                <select
                    id="is_remote"
                    name="is_remote"
                    value={filters.is_remote}
                    onChange={handleChange}
                >
                    <option value="">Remote and on-site</option>
                    <option value="1">Remote opportunities</option>
                    <option value="0">On-site opportunities</option>
                </select>
            </div>

            <fieldset className="job-filter-salary">
                <legend>Monthly salary (BGN)</legend>
                <div>
                    <label htmlFor="salary_min">
                        <span>Minimum</span>
                        <input
                            id="salary_min"
                            name="salary_min"
                            type="number"
                            min="0"
                            step="100"
                            value={filters.salary_min}
                            onChange={handleChange}
                            placeholder="From"
                        />
                    </label>
                    <label htmlFor="salary_max">
                        <span>Maximum</span>
                        <input
                            id="salary_max"
                            name="salary_max"
                            type="number"
                            min="0"
                            step="100"
                            value={filters.salary_max}
                            onChange={handleChange}
                            placeholder="To"
                        />
                    </label>
                </div>
            </fieldset>

            <div className="job-filter-field">
                <label htmlFor="sort">Sort results</label>
                <select
                    id="sort"
                    name="sort"
                    value={filters.sort}
                    onChange={handleChange}
                >
                    <option value="latest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="salary_desc">Highest salary</option>
                    <option value="salary_asc">Lowest salary</option>
                </select>
            </div>

            <div className="job-filter-field">
                <label htmlFor="per_page">Results per page</label>
                <select
                    id="per_page"
                    name="per_page"
                    value={filters.per_page}
                    onChange={handleChange}
                >
                    <option value="12">12 jobs</option>
                    <option value="24">24 jobs</option>
                    <option value="48">48 jobs</option>
                </select>
            </div>

            <div className="job-filter-actions">
                <button
                    type="submit"
                    className="job-filter-apply"
                    disabled={loading}
                >
                    {loading ? 'Loading…' : 'Show jobs'}
                </button>
                <button
                    type="button"
                    onClick={onReset}
                    className="job-filter-reset"
                >
                    Clear all
                </button>
            </div>
        </form>
    )
}
