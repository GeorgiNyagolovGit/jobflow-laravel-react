import './AdminStats.css'

export default function AdminStats({ stats = {} }) {
    const cards = [
        ['Users', stats.users ?? 0],
        ['Jobs', stats.jobs ?? 0],
        ['Companies', stats.companies ?? 0],
        ['Applications', stats.applications ?? 0],
    ]

    return (
        <div className="admin-stats">
            {cards.map(([label, value]) => (
                <article key={label} className="stat-card">
                    <strong>{value}</strong>
                    <span>{label}</span>
                </article>
            ))}
        </div>
    )
}
