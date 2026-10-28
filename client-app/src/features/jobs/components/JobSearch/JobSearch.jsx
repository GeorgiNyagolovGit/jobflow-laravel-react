import './JobSearch.css'

export default function JobSearch({ value, onChange, onSearch, loading }) {
    function handleSubmit(event) {
        event.preventDefault()
        onSearch()
    }

    return (
        <form className="job-search" onSubmit={handleSubmit}>
            <div className="job-search-field">
                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                    focusable="false"
                >
                    <path d="m21 21-4.35-4.35m2.35-5.15a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                </svg>
                <label htmlFor="job-search-input">
                    What job are you looking for?
                </label>
                <input
                    id="job-search-input"
                    type="search"
                    value={value}
                    onChange={(event) => onChange(event.target.value)}
                    placeholder="Job title, skill, or keyword"
                />
            </div>

            <button type="submit" disabled={loading}>
                Search jobs
            </button>
        </form>
    )
}
