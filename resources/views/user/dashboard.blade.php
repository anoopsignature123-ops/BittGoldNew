@extends('user.layouts.master')

@push('title')
    My Dashboard
@endpush

@push('styles')
<style>
/* ============================================================
   DASHBOARD — PREMIUM GOLD THEME
   ============================================================ */

/* ---------- CSS Variables ---------- */
:root {
    --g1: #f5c842;         /* Gold primary */
    --g2: #e8a800;         /* Gold deep */
    --g3: #ffe88a;         /* Gold light / highlight */
    --g4: #b87c10;         /* Gold dark / border accent */
    --g-glow: rgba(245, 189, 50, 0.22);
    --g-glow-sm: rgba(245, 189, 50, 0.12);
    --navy-1: #05111e;     /* Deepest bg */
    --navy-2: #071928;     /* Card bg */
    --navy-3: #0b2035;     /* Lighter card bg */
    --navy-4: #0e2a44;     /* Hover bg */
    --navy-border: rgba(184, 140, 30, 0.28);
    --navy-border-hover: rgba(245, 189, 50, 0.55);
    --text-hi: #f0f4f9;    /* High contrast text */
    --text-lo: #7a96b0;    /* Muted text */
    --green: #3dcc88;
}

/* ---------- Shared card shell ---------- */
.db-card {
    background: linear-gradient(155deg, var(--navy-3) 0%, var(--navy-2) 100%);
    border: 1px solid var(--navy-border);
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.22s, box-shadow 0.22s;
}
.db-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(ellipse at 90% -10%, rgba(245,189,50,0.08) 0%, transparent 55%);
    pointer-events: none;
}
.db-card:hover {
    border-color: var(--navy-border-hover);
    box-shadow: 0 8px 32px rgba(0,0,0,0.38), 0 0 0 1px rgba(245,189,50,0.1) inset;
}

/* Gold shimmer top-border accent */
.db-card-accent::after {
    content: '';
    position: absolute;
    top: 0; left: 12%; right: 12%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--g1), var(--g3), var(--g1), transparent);
    border-radius: 0 0 4px 4px;
    opacity: 0.7;
}

/* ============================================================
   HERO SECTION
   ============================================================ */
.db-hero {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 220px;
    padding: 2.2rem 2.4rem;
    border-radius: 18px;
    background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    border: 1px solid rgba(184, 140, 30, 0.35);
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(245, 189, 50, 0.06) inset;
    margin-bottom: 1.5rem;
}

/* Gold haze at right */
.db-hero::after {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 55%;
    background: radial-gradient(ellipse at 80% 50%, rgba(245,189,50,0.1) 0%, transparent 65%);
    pointer-events: none;
}

/* Horizontal gold rule at top */
.db-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 8%; right: 8%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--g1), var(--g3), var(--g1), transparent);
    opacity: 0.65;
    border-radius: 0 0 6px 6px;
}

.db-hero-bg-art {
    position: absolute;
    right: 218px;
    top: 0; bottom: 0;
    width: 350px;
    pointer-events: none;
    opacity: 0.6;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.db-hero-bg-art svg { width: 100%; height: 100%; }

.db-hero-left {
    position: relative;
    z-index: 2;
    flex: 1;
}

.db-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(245,189,50,0.1);
    border: 1px solid rgba(245,189,50,0.25);
    color: var(--g1);
    font-size: 0.67rem;
    font-weight: 800;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    margin-bottom: 0.7rem;
    padding: 0.28rem 0.7rem 0.28rem 0.55rem;
    border-radius: 30px;
}

.db-hero-title {
    font-size: 2.05rem;
    font-weight: 800;
    color: var(--text-hi);
    margin: 0 0 0.45rem;
    line-height: 1.2;
    text-shadow: 0 2px 12px rgba(0,0,0,0.4);
}

.db-hero-title span {
    color: var(--g1);
    text-shadow: 0 0 24px rgba(245,189,50,0.45);
}

.db-hero-sub {
    font-size: 0.82rem;
    color: var(--text-lo);
    margin-bottom: 1.3rem;
    max-width: 400px;
    line-height: 1.6;
}

.db-invest-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.62rem 1.4rem;
    background: linear-gradient(135deg, var(--g1) 0%, var(--g2) 100%);
    border: 1px solid var(--g4);
    border-radius: 9px;
    color: #0d1a25;
    font-weight: 800;
    font-size: 0.83rem;
    text-decoration: none;
    transition: all 0.22s;
    box-shadow: 0 4px 18px rgba(245,189,50,0.3), 0 1px 0 rgba(255,255,255,0.15) inset;
    letter-spacing: 0.02em;
}

.db-invest-btn:hover {
    background: linear-gradient(135deg, var(--g3) 0%, var(--g1) 100%);
    color: #07121c;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(245,189,50,0.45);
}

/* Earning Wallet Box */
.db-hero-wallet {
    position: relative;
    z-index: 2;
    min-width: 205px;
    padding: 1.2rem 1.4rem 1.1rem;
    background: linear-gradient(155deg, rgba(11,26,42,0.95), rgba(6,14,26,0.95));
    border: 1px solid rgba(184,140,30,0.4);
    border-radius: 14px;
    backdrop-filter: blur(10px);
    text-align: left;
    flex-shrink: 0;
    margin-left: 1.5rem;
    box-shadow: 0 8px 28px rgba(0,0,0,0.45), 0 0 0 1px rgba(245,189,50,0.06) inset;
    overflow: hidden;
}

/* Top accent on wallet */
.db-hero-wallet::before {
    content: '';
    position: absolute;
    top: 0; left: 15%; right: 15%;
    height: 1.5px;
    background: linear-gradient(90deg, transparent, var(--g1), transparent);
    opacity: 0.8;
}

.db-hero-wallet-label {
    font-size: 0.7rem;
    color: var(--text-lo);
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.3rem;
}

.db-hero-wallet-amount {
    font-size: 1.65rem;
    font-weight: 800;
    color: var(--g3);
    display: block;
    line-height: 1.2;
    margin-bottom: 0.5rem;
    text-shadow: 0 0 20px rgba(245,189,50,0.3);
}

.db-hero-wallet-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.72rem;
    color: var(--text-lo);
    font-weight: 600;
}

.db-hero-wallet-status .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 6px var(--green);
    flex-shrink: 0;
}

.db-hero-wallet-status .dot.inactive {
    background: #f7a640;
    box-shadow: 0 0 6px #f7a640;
}

.db-hero-wallet-icon {
    position: absolute;
    top: 1rem; right: 1rem;
    opacity: 0.18;
    font-size: 2.2rem;
    color: var(--g1);
}

/* ============================================================
   STAT CARDS ROW
   ============================================================ */
.db-stat-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.4rem;
}

.db-stat-card {
   background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    border: 1px solid var(--navy-border);
    border-radius: 14px;
    padding: 1.25rem 1.35rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.22s;
    position: relative;
    overflow: hidden;
}

/* Bottom gold line */
.db-stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(245,189,50,0.5), transparent);
    border-radius: 0 0 14px 14px;
    opacity: 0;
    transition: opacity 0.22s;
}

.db-stat-card:hover {
    border-color: var(--navy-border-hover);
    box-shadow: 0 6px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(245,189,50,0.08) inset;
    transform: translateY(-2px);
}

.db-stat-card:hover::after {
    opacity: 1;
}

/* Gold shimmer top */
.db-stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 20%; right: 20%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(245,189,50,0.4), transparent);
}

.db-stat-card-info small {
    display: block;
    font-size: 0.69rem;
    color: var(--text-lo);
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 0.4rem;
}

.db-stat-card-info h3 {
    font-size: 1.55rem;
    font-weight: 800;
    color: var(--g3);
    margin: 0;
    line-height: 1.2;
    text-shadow: 0 0 16px rgba(245,189,50,0.25);
}

.db-stat-card-info h3.text-success {
    color: var(--green) !important;
    text-shadow: 0 0 16px rgba(61,204,136,0.3);
}

.db-stat-card-icon {
    width: 48px; height: 48px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, rgba(245,189,50,0.16), rgba(245,189,50,0.06));
    border: 1px solid rgba(245,189,50,0.28);
    color: var(--g1);
    font-size: 1.35rem;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(245,189,50,0.12);
    transition: all 0.22s;
}

.db-stat-card:hover .db-stat-card-icon {
    background: linear-gradient(145deg, rgba(245,189,50,0.28), rgba(245,189,50,0.12));
    box-shadow: 0 4px 20px rgba(245,189,50,0.25);
}

/* ============================================================
   MAIN CONTENT ROW
   ============================================================ */
.db-main-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

/* ---- Earnings Summary ---- */
.db-earnings-card {
       background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    border: 1px solid var(--navy-border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
}

.db-earnings-card::before {
    content: '';
    position: absolute;
    top: 0; left: 10%; right: 10%;
    height: 2px;
       background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    opacity: 0.6;
}

.db-earnings-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1.35rem 1.5rem 1rem;
}

.db-section-eyebrow {
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.15em;
    color: var(--g2);
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.3rem;
}

.db-earnings-head h4 {
    color: var(--text-hi);
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
}

.db-view-history {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.76rem;
    font-weight: 700;
    color: var(--g1);
    text-decoration: none;
    white-space: nowrap;
    padding: 0.28rem 0.7rem;
    border: 1px solid rgba(245,189,50,0.25);
    border-radius: 20px;
    background: rgba(245,189,50,0.06);
    transition: all 0.2s;
}

.db-view-history:hover {
    color: var(--g3);
    background: rgba(245,189,50,0.12);
    border-color: rgba(245,189,50,0.5);
}

.db-earnings-table {
    width: 100%;
    border-collapse: collapse;
}

.db-earnings-table thead tr {
    background: rgba(5,12,22,0.55);
    border-bottom: 1px solid rgba(184,140,30,0.2);
}

.db-earnings-table thead th {
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    color: var(--g2);
    text-transform: uppercase;
    padding: 0.65rem 1.5rem;
}

.db-earnings-table thead th:not(:first-child) { text-align: right; }

.db-earnings-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.04);
    transition: background 0.15s;
}

.db-earnings-table tbody tr:last-child { border-bottom: none; }

.db-earnings-table tbody tr:hover {
    background: rgba(245,189,50,0.04);
}

.db-earnings-table td {
    padding: 0.9rem 1.5rem;
    font-size: 0.82rem;
    color: #c0d0e0;
}

.db-earnings-table td:not(:first-child) {
    text-align: right;
    font-weight: 700;
    color: var(--green);
    font-size: 0.85rem;
    font-variant-numeric: tabular-nums;
}

.db-income-type {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #ccdae8;
}

.db-income-type i { font-size: 1.1rem; }

/* ---- Quick Actions ---- */
.db-quick-card {
    background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    border: 1px solid var(--navy-border);
    border-radius: 16px;
    padding: 1.35rem 1.5rem;
    position: relative;
    overflow: hidden;
}

.db-quick-card::before {
    content: '';
    position: absolute;
    top: 0; left: 10%; right: 10%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--g1), var(--g3), var(--g1), transparent);
    opacity: 0.6;
}

.db-quick-card h4 {
    color: var(--text-hi);
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    margin: 0 0 1.1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.db-quick-card h4::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(184,140,30,0.35), transparent);
}

.db-quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.9rem;
}

.db-quick-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.1rem;
    background: rgba(5,14,28,0.7);
    border: 1px solid rgba(184,140,30,0.2);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.22s;
    gap: 0.8rem;
    position: relative;
    overflow: hidden;
}

/* Hover glow dot in corner */
.db-quick-item::after {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 55px; height: 55px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,189,50,0.15), transparent 70%);
    opacity: 0;
    transition: opacity 0.25s;
}

.db-quick-item:hover::after { opacity: 1; }

.db-quick-item:hover {
    border-color: rgba(245,189,50,0.48);
    background: rgba(12,28,48,0.85);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.28), 0 0 0 1px rgba(245,189,50,0.08) inset;
}

.db-quick-item-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
    min-width: 0;
}

.db-quick-item-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, rgba(245,189,50,0.16), rgba(245,189,50,0.05));
    border: 1px solid rgba(245,189,50,0.25);
    color: var(--g1);
    font-size: 1.15rem;
    flex-shrink: 0;
    transition: all 0.22s;
    box-shadow: 0 2px 10px rgba(245,189,50,0.1);
}

.db-quick-item:hover .db-quick-item-icon {
    background: linear-gradient(145deg, rgba(245,189,50,0.28), rgba(245,189,50,0.1));
    box-shadow: 0 4px 16px rgba(245,189,50,0.22);
}

.db-quick-item-text strong {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: #dae6f3;
    line-height: 1.3;
}

.db-quick-item-text small {
    display: block;
    font-size: 0.69rem;
    color: var(--text-lo);
    margin-top: 2px;
    line-height: 1.4;
}

.db-quick-item-arrow {
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(245,189,50,0.1);
    border: 1px solid rgba(245,189,50,0.2);
    color: var(--g1);
    font-size: 0.82rem;
    flex-shrink: 0;
    transition: all 0.22s;
}

.db-quick-item:hover .db-quick-item-arrow {
    background: rgba(245,189,50,0.22);
    border-color: rgba(245,189,50,0.5);
    transform: translateX(2px);
}

/* ============================================================
   BOTTOM ROW — CHART + CAREER RANK
   ============================================================ */
.db-bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

.db-chart-card,
.db-rank-card {
   background: linear-gradient(110deg, #302401 0%, #221a01 50%, #261c00 100%);
    border: 1px solid var(--navy-border);
    border-radius: 16px;
    padding: 1.35rem 1.5rem;
    position: relative;
    overflow: hidden;
}

.db-chart-card::before,
.db-rank-card::before {
    content: '';
    position: absolute;
    top: 0; left: 10%; right: 10%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--g1), var(--g3), var(--g1), transparent);
    opacity: 0.55;
}

.db-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.db-card-head h4 {
    color: var(--text-hi);
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 7px;
}

.db-card-head h4::before {
    content: '';
    display: inline-block;
    width: 3px; height: 14px;
    background: linear-gradient(180deg, var(--g1), var(--g2));
    border-radius: 2px;
    flex-shrink: 0;
}

.db-chart-filter select {
    background: rgba(5,14,28,0.85);
    border: 1px solid rgba(184,140,30,0.3);
    border-radius: 8px;
    color: #a0b8cc;
    font-size: 0.74rem;
    padding: 0.32rem 0.7rem;
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s;
}

.db-chart-filter select:focus {
    border-color: rgba(245,189,50,0.55);
}

.db-chart-filter select option { background: #091a2b; }

.db-chart-legend {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    margin-bottom: 0.8rem;
}

.db-chart-legend-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.71rem;
    color: var(--text-lo);
    font-weight: 600;
}

.db-chart-legend-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
}

.db-chart-wrap {
    position: relative;
    height: 135px;
}

/* ---- Career Rank ---- */
.db-rank-body {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    padding: 0.9rem 0 0.4rem;
}

.db-rank-badge {
    width: 80px; height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, rgba(245,189,50,0.18), rgba(232,168,0,0.07));
    border: 2px solid rgba(245,189,50,0.35);
    flex-shrink: 0;
    position: relative;
    box-shadow: 0 0 0 6px rgba(245,189,50,0.06), 0 8px 24px rgba(0,0,0,0.3);
}

.db-rank-badge::after {
    content: '';
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    border: 1px solid rgba(245,189,50,0.12);
}

.db-rank-badge i {
    font-size: 2.1rem;
    color: var(--g1);
    filter: drop-shadow(0 0 8px rgba(245,189,50,0.4));
}

.db-rank-info h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--g1);
    margin: 0 0 0.35rem;
    text-shadow: 0 0 20px rgba(245,189,50,0.35);
}

.db-rank-info p {
    font-size: 0.78rem;
    color: var(--text-lo);
    margin: 0;
    line-height: 1.6;
}

.db-rank-info a {
    color: var(--g1);
    text-decoration: none;
    font-weight: 700;
    border-bottom: 1px dashed rgba(245,189,50,0.35);
}

.db-rank-info a:hover {
    color: var(--g3);
    border-bottom-color: var(--g3);
}

/* Quick Action Referral Link Card */
.db-quick-ref-box {
    grid-column: span 2; /* Ise poori row mein failane ke liye */
    background: rgba(5, 14, 28, 0.85);
    border: 1px solid rgba(184, 140, 30, 0.3);
    border-radius: 12px;
    padding: 1rem 1.1rem;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.db-quick-ref-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--g1);
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.db-quick-ref-input-group {
    display: flex;
    align-items: center;
    background: rgba(2, 7, 15, 0.9);
    border: 1px solid rgba(184, 140, 30, 0.25);
    border-radius: 8px;
    padding: 0.25rem 0.25rem 0.25rem 0.7rem;
    gap: 8px;
}
.db-quick-ref-input {
    background: transparent;
    border: none;
    color: var(--text-hi);
    font-size: 0.76rem;
    width: 100%;
    outline: none;
    font-family: monospace;
}
.db-quick-ref-btn {
    background: linear-gradient(135deg, var(--g1) 0%, var(--g2) 100%);
    border: none;
    color: #0d1a25;
    font-weight: 700;
    font-size: 0.72rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
    white-space: nowrap;
}
.db-quick-ref-btn:hover {
    background: linear-gradient(135deg, var(--g3) 0%, var(--g1) 100%);
}

@media (max-width: 575px) {
    .db-quick-ref-box { grid-column: span 1; }
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1199px) {
    .db-stat-row { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 991px) {
    .db-main-row,
    .db-bottom-row { grid-template-columns: 1fr; }

    .db-hero {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.3rem;
        min-height: auto;
    }

    .db-hero-wallet {
        margin-left: 0;
        width: 100%;
    }
}

@media (max-width: 575px) {
    .db-stat-row { grid-template-columns: 1fr; }
    .db-quick-grid { grid-template-columns: 1fr; }
    .db-hero { padding: 1.5rem; }
    .db-hero-title { font-size: 1.55rem; }
    .db-quick-item-text small { display: none; }
}
</style>
@endpush

@section('content')
<div class="content-wrapper">

    {{-- ================================================
         HERO SECTION
         ================================================ --}}
    <section class="db-hero mb-0">
        {{-- Decorative chart art --}}
        <div class="db-hero-bg-art" aria-hidden="true">
            <svg viewBox="0 0 360 210" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
                <defs>
                    <linearGradient id="heroGradFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f5c842" stop-opacity="0.55"/>
                        <stop offset="100%" stop-color="#f5c842" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#e8a800" stop-opacity="0.3"/>
                        <stop offset="60%" stop-color="#f5c842" stop-opacity="0.85"/>
                        <stop offset="100%" stop-color="#ffe88a" stop-opacity="0.9"/>
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="2.5" result="blur"/>
                        <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
                    </filter>
                </defs>
                <!-- Filled area under the line -->
                <path d="M15,185 70,145 115,155 170,100 220,78 280,42 330,18 330,200 15,200 Z"
                      fill="url(#heroGradFill)" opacity="0.22"/>
                <!-- Grid lines subtle -->
                <line x1="15" y1="60"  x2="340" y2="60"  stroke="rgba(245,189,50,0.07)" stroke-width="1"/>
                <line x1="15" y1="110" x2="340" y2="110" stroke="rgba(245,189,50,0.07)" stroke-width="1"/>
                <line x1="15" y1="160" x2="340" y2="160" stroke="rgba(245,189,50,0.07)" stroke-width="1"/>
                <!-- Main chart line -->
                <polyline points="15,185 70,145 115,155 170,100 220,78 280,42 330,18"
                          stroke="url(#lineGrad)" stroke-width="2.5"
                          stroke-linecap="round" stroke-linejoin="round" fill="none"
                          filter="url(#glow)"/>
                <!-- Data points -->
                <circle cx="15"  cy="185" r="3.5" fill="#e8a800" opacity="0.6"/>
                <circle cx="70"  cy="145" r="3.5" fill="#f5c842" opacity="0.7"/>
                <circle cx="115" cy="155" r="3.5" fill="#f5c842" opacity="0.7"/>
                <circle cx="170" cy="100" r="4"   fill="#f5c842" opacity="0.85"/>
                <circle cx="220" cy="78"  r="4"   fill="#f5c842" opacity="0.85"/>
                <circle cx="280" cy="42"  r="4.5" fill="#ffe88a" opacity="0.9"/>
                <!-- Arrow head at tip -->
                <polygon points="330,18 315,28 321,38" fill="rgba(255,232,138,0.8)" filter="url(#glow)"/>
                <!-- Decorative coin rings -->
                <ellipse cx="300" cy="178" rx="28" ry="7"  fill="rgba(245,189,50,0.1)" stroke="rgba(245,189,50,0.2)" stroke-width="1"/>
                <ellipse cx="300" cy="171" rx="28" ry="7"  fill="rgba(245,189,50,0.08)" stroke="rgba(245,189,50,0.15)" stroke-width="1"/>
                <ellipse cx="300" cy="164" rx="28" ry="7"  fill="rgba(245,189,50,0.12)" stroke="rgba(245,189,50,0.25)" stroke-width="1"/>
                <!-- Star sparkles -->
                <circle cx="50"  cy="40" r="1.5" fill="rgba(255,232,138,0.6)"/>
                <circle cx="240" cy="30" r="1.2" fill="rgba(255,232,138,0.5)"/>
                <circle cx="310" cy="90" r="1.8" fill="rgba(255,232,138,0.55)"/>
            </svg>
        </div>

        <div class="db-hero-left">
            <div class="db-hero-eyebrow">
                <i class="mdi mdi-account-circle"></i>
                Member Dashboard
            </div>
            <h1 class="db-hero-title">
                Welcome back, <span>{{ $user->name ?? 'User' }}</span>
            </h1>
            <p class="db-hero-sub">Track your funds, investments, earnings and referral progress from one place.</p>
            <a href="{{ route('user.investment.index') }}" class="db-invest-btn">
                <i class="mdi mdi-rocket-launch"></i> Invest Now
            </a>
        </div>

        <div class="db-hero-wallet">
            <i class="mdi mdi-wallet db-hero-wallet-icon" aria-hidden="true"></i>
            <span class="db-hero-wallet-label">Earning Wallet</span>
            <span class="db-hero-wallet-amount">₹ {{ $earningWallet }}</span>
            <span class="db-hero-wallet-status">
                @if(($user->status ?? 'active') === 'active')
                    <span class="dot"></span> Account Active
                @else
                    <span class="dot inactive"></span> Account Inactive
                @endif
            </span>
        </div>
    </section>

    {{-- ================================================
         STAT CARDS
         ================================================ --}}
    <div class="db-stat-row mt-4">
        <div class="db-stat-card">
            <div class="db-stat-card-info">
                <small>Deposit Wallet</small>
                <h3>₹ {{ $depositWallet }}</h3>
            </div>
            <div class="db-stat-card-icon">
                <i class="mdi mdi-wallet"></i>
            </div>
        </div>

        <div class="db-stat-card">
            <div class="db-stat-card-info">
                <small>Total Earned</small>
                <h3 class="text-success">₹ {{ $totalEarned }}</h3>
            </div>
            <div class="db-stat-card-icon">
                <i class="mdi mdi-cash-multiple"></i>
            </div>
        </div>

        <div class="db-stat-card">
            <div class="db-stat-card-info">
                <small>Active Package</small>
                <h3>
                    @if(is_numeric(str_replace([',', ' '], '', $activePackage)))
                        ₹ {{ $activePackage }}
                    @else
                        {{ $activePackage }}
                    @endif
                </h3>
            </div>
            <div class="db-stat-card-icon">
                <i class="mdi mdi-gift-outline"></i>
            </div>
        </div>

        <div class="db-stat-card">
            <div class="db-stat-card-info">
                <small>Direct Referrals</small>
                <h3>{{ $directReferrals }}</h3>
            </div>
            <div class="db-stat-card-icon">
                <i class="mdi mdi-account-multiple"></i>
            </div>
        </div>
    </div>

    {{-- ================================================
         EARNINGS SUMMARY + QUICK ACTIONS
         ================================================ --}}
    <div class="db-main-row">

        {{-- Earnings Summary --}}
        <div class="db-earnings-card">
            <div class="db-earnings-head">
                <div>
                    <span class="db-section-eyebrow">Financial Overview</span>
                    <h4>Earnings Summary</h4>
                </div>
                <a href="{{ route('user.income.index') }}" class="db-view-history">
                    View History <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
            <table class="db-earnings-table">
                <thead>
                    <tr>
                        <th>Income Type</th>
                        <th>Today Income</th>
                        <th>Total Income</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="db-income-type">
                                <i class="mdi mdi-account-plus text-warning"></i>
                                Referral Income
                            </span>
                        </td>
                        <td>₹ {{ number_format($earnings['referral']['today'], 2) }}</td>
                        <td>₹ {{ number_format($earnings['referral']['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="db-income-type">
                                <i class="mdi mdi-layers" style="color:#36adec;"></i>
                                Level Income
                            </span>
                        </td>
                        <td>₹ {{ number_format($earnings['level']['today'], 2) }}</td>
                        <td>₹ {{ number_format($earnings['level']['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="db-income-type">
                                <i class="mdi mdi-chart-line" style="color:#48d597;"></i>
                                Trade Profit Income
                            </span>
                        </td>
                        <td>₹ {{ number_format($earnings['trade_profit']['today'], 2) }}</td>
                        <td>₹ {{ number_format($earnings['trade_profit']['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="db-income-type">
                                <i class="mdi mdi-trophy text-warning"></i>
                                Leadership Report / Bonus
                            </span>
                        </td>
                        <td>₹ {{ number_format($earnings['leadership']['today'], 2) }}</td>
                        <td>₹ {{ number_format($earnings['leadership']['total'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Quick Actions --}}
        {{-- Quick Actions --}}
        <div class="db-quick-card">
            <h4>Quick Actions</h4>
            <div class="db-quick-grid">
                <a href="{{ route('user.deposit.index') }}" class="db-quick-item">
                    <div class="db-quick-item-left">
                        <div class="db-quick-item-icon">
                            <i class="mdi mdi-wallet-plus"></i>
                        </div>
                        <div class="db-quick-item-text">
                            <strong>Add Funds</strong>
                            <small>Top up your wallet easily</small>
                        </div>
                    </div>
                    <div class="db-quick-item-arrow">
                        <i class="mdi mdi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('user.investment.index') }}" class="db-quick-item">
                    <div class="db-quick-item-left">
                        <div class="db-quick-item-icon">
                            <i class="mdi mdi-gift-outline"></i>
                        </div>
                        <div class="db-quick-item-text">
                            <strong>Buy Package</strong>
                            <small>Upgrade your package and grow more</small>
                        </div>
                    </div>
                    <div class="db-quick-item-arrow">
                        <i class="mdi mdi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('user.team.index') }}" class="db-quick-item">
                    <div class="db-quick-item-left">
                        <div class="db-quick-item-icon">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                        <div class="db-quick-item-text">
                            <strong>My Team</strong>
                            <small>View and manage your team</small>
                        </div>
                    </div>
                    <div class="db-quick-item-arrow">
                        <i class="mdi mdi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('user.rank.index') }}" class="db-quick-item">
                    <div class="db-quick-item-left">
                        <div class="db-quick-item-icon">
                            <i class="mdi mdi-trophy"></i>
                        </div>
                        <div class="db-quick-item-text">
                            <strong>Rank Report</strong>
                            <small>Check your rank and earned position</small>
                        </div>
                    </div>
                    <div class="db-quick-item-arrow">
                        <i class="mdi mdi-arrow-right"></i>
                    </div>
                </a>

                {{-- Referral Link Box inside Quick Actions --}}
                <div class="db-quick-ref-box">
                    <span class="db-quick-ref-label">
                        <i class="mdi mdi-link-variant"></i> Your Referral Link
                    </span>
                    <div class="db-quick-ref-input-group">
                        <input type="text" id="userReferralLink" class="db-quick-ref-input" value="{{ route('user.register', ['ref' => $user->referral_code]) }}" readonly>
                        <button type="button" class="db-quick-ref-btn" onclick="copyReferralLink()">
                            <i class="mdi mdi-content-copy"></i> Copy
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ================================================
         EARNINGS CHART + CAREER RANK
         ================================================ --}}
    <div class="db-bottom-row">

        {{-- Earnings Chart --}}
        <div class="db-chart-card">
            <div class="db-card-head">
                <h4>Earnings Chart</h4>
                <div class="db-chart-filter">
                    <select id="dbChartFilter" aria-label="Chart period">
                        <option value="7">7 Days</option>
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                    </select>
                </div>
            </div>
            <div class="db-chart-legend">
                <span class="db-chart-legend-item">
                    <span class="db-chart-legend-dot" style="background:#f5c842; box-shadow: 0 0 5px rgba(245,200,66,0.5);"></span>
                    Today's Income
                </span>
                <span class="db-chart-legend-item">
                    <span class="db-chart-legend-dot" style="background:#4e6e8c; border: 1px dashed #6a8fac;"></span>
                    Total Income
                </span>
            </div>
            <div class="db-chart-wrap">
                <canvas id="dbEarningsChart" aria-label="Earnings chart"></canvas>
            </div>
        </div>

        {{-- Career Rank --}}
        <div class="db-rank-card">
            <div class="db-card-head">
                <h4>Career Rank</h4>
            </div>
            <div class="db-rank-body">
                <div class="db-rank-badge">
                    <i class="mdi mdi-trophy"></i>
                </div>
                <div class="db-rank-info">
                    <h3>{{ $currentRank }}</h3>
                    <p>
                        Check your power leg and weaker leg volume in the
                        <a href="{{ route('user.rank.index') }}">Rewards &amp; Rank Report</a>.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var ctx = document.getElementById('dbEarningsChart');
    if (!ctx) return;

    var labels     = @json($chartLabels);
    var todayData  = @json($chartDays);
    var totalData  = @json($chartDaysTotal);

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Today's Income",
                    data: todayData,
                    borderColor: '#f5c842',
                    backgroundColor: function(context) {
                        var c = context.chart.ctx;
                        var gradient = c.createLinearGradient(0, 0, 0, 135);
                        gradient.addColorStop(0, 'rgba(245,200,66,0.28)');
                        gradient.addColorStop(1, 'rgba(245,200,66,0.02)');
                        return gradient;
                    },
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f5c842',
                    pointBorderColor: '#ffe88a',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#ffe88a',
                    tension: 0.42,
                    fill: true,
                },
                {
                    label: 'Total Income',
                    data: totalData,
                    borderColor: 'rgba(78,110,140,0.65)',
                    backgroundColor: 'rgba(78,110,140,0.04)',
                    borderWidth: 1.5,
                    borderDash: [5, 4],
                    pointBackgroundColor: '#4e6e8c',
                    pointBorderColor: '#6a8fac',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.42,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(5,14,28,0.97)',
                    borderColor: 'rgba(245,189,50,0.35)',
                    borderWidth: 1,
                    titleColor: '#f5c842',
                    bodyColor: '#a8bdd0',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(item) {
                            return '  \u20B9 ' + parseFloat(item.parsed.y).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(184,140,30,0.07)', drawBorder: false },
                    ticks: { color: '#5a7489', font: { size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(184,140,30,0.07)', drawBorder: false },
                    ticks: {
                        color: '#5a7489',
                        font: { size: 10 },
                        callback: function(v) { return '\u20B9 ' + parseFloat(v).toFixed(1); }
                    },
                    beginAtZero: true
                }
            }
        }
    });
})();

function copyReferralLink() {
    var copyText = document.getElementById("userReferralLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    var btn = event.currentTarget;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="mdi mdi-check"></i> Copied!';
    setTimeout(function() {
        btn.innerHTML = originalHTML;
    }, 2000);
}
</script>
@endpush
