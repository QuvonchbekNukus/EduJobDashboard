@once
<style>
    :root {
        --ink: #111827;
        --muted: #6b7280;
        --brand: #0ea5e9;
        --brand-dark: #0369a1;
        --accent: #f97316;
        --surface: #ffffff;
        --soft: #f8fafc;
    }
    body.modern-body {
        font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
        color: var(--ink);
        background: radial-gradient(1200px 600px at 10% -10%, rgba(14, 165, 233, 0.2), transparent 60%),
                    radial-gradient(900px 500px at 90% 10%, rgba(249, 115, 22, 0.18), transparent 55%),
                    linear-gradient(180deg, #f8fafc 0%, #ffffff 60%) !important;
        min-height: 100vh;
    }
    .modern-shell {
        padding: 1.5rem 0 3rem;
    }
    .modern-hero {
        border-radius: 1.75rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.18), rgba(249, 115, 22, 0.16));
        border: 1px solid rgba(14, 165, 233, 0.2);
        box-shadow: 0 24px 50px -40px rgba(15, 23, 42, 0.6);
    }
    .modern-title {
        font-family: "Unbounded", "Space Grotesk", sans-serif;
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }
    .modern-card {
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(148, 163, 184, 0.25);
        box-shadow: 0 24px 50px -45px rgba(15, 23, 42, 0.6);
    }
    .modern-card h5 {
        font-weight: 600;
    }
    .badge-soft {
        background: rgba(14, 165, 233, 0.15);
        color: var(--brand-dark);
        font-weight: 600;
        border-radius: 999px;
        padding: 0.35rem 0.8rem;
        font-size: 0.8rem;
    }
    .modern-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.3);
        font-size: 0.82rem;
        background: white;
        font-weight: 600;
        color: var(--ink);
    }
    .btn-brand {
        background: var(--brand);
        border-color: var(--brand);
        color: white;
        font-weight: 600;
    }
    .btn-brand:hover {
        background: var(--brand-dark);
        border-color: var(--brand-dark);
        color: white;
    }
    .btn-outline-ink {
        border: 1px solid rgba(15, 23, 42, 0.2);
        color: var(--ink);
        font-weight: 600;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: rgba(14, 165, 233, 0.6);
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.15);
    }
    .modern-table thead th {
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        color: var(--muted);
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }
    .modern-table tbody td {
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    .modern-alert {
        border-radius: 1rem;
        border: 1px solid rgba(34, 197, 94, 0.2);
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
    }
    .danger-card {
        border: 1px solid rgba(239, 68, 68, 0.3);
        background: rgba(254, 226, 226, 0.6);
    }
    .icon-bubble {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(249, 115, 22, 0.15);
        color: var(--accent);
        font-size: 1.1rem;
    }
    .link-brand {
        color: var(--brand-dark);
        font-weight: 600;
        text-decoration: none;
    }
    .link-brand:hover {
        color: var(--brand);
    }
    @media (max-width: 767.98px) {
        .modern-hero {
            padding: 1.5rem;
        }
    }
</style>
@endonce
