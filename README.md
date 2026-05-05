# RPSB Mini ERP - PTS Maintenance Cost Module

A functional MVP ERP module built for the **Pusat Timbang Sawit (PTS)** maintenance ecosystem. This system tracks staff costs, vehicle maintenance, and facility repairs while managing budget approval workflows between HQ and branch locations.

---

## 🚀 Live Demo & Credentials
**URL:** `[https://pts-maintenance-production.up.railway.app/]`

| Role | Email | Password |
| :--- | :--- | :--- |
| **HQ Admin** | `admin@pts.com` | `password` |
| **Supervisor 1** | `sv_sa@pts.com` | `password` |
| **Supervisor 2** | `sv_klang@pts.com` | `password` |
| **Supervisor 3** | `sv_subang@pts.com` | `password` |

---

## 🛠️ Tech Stack
- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue.js 3 via Inertia.js (Composition API)
- **Database:** PostgreSQL (or MySQL)
- **Styling:** Tailwind CSS
- **Infrastructure:** Railway (Cloud Hosting & CI/CD)

---

## ✨ Key Features & Business Logic

### 🔐 Role-Based Access Control (RBAC)
- **HQ Admin:** Full visibility of all PTS locations, budget oversight, and global reporting.
- **Supervisor:** Restricted view; can only manage assets and staff within their assigned PTS.

### 🚛 Smart Lorry Maintenance
- Automated **"Recurring Issue"** flag triggers if a vehicle is serviced more than twice in 30 days.

### 🧩 Flexible Asset Management
- Uses **JSON Metadata** for "Other Assets" (Weighbridge, Genset, Buildings).
- Supports flexible fields like calibration dates without rigid schemas.

### 💰 Budget Flow
- Supervisors can request budget extensions once spending hits **80% of the monthly limit**.
- Approved requests automatically increase the monthly budget ceiling.

### 🧾 Audit Trail
- Tracks creator and last editor for every financial record.

---

## 📊 API Documentation

### 1. GET `/api/v1/pts/{id}/maintenance-summary?month=YYYY-MM`
Returns a summary of maintenance costs for a specific PTS and month.

### 2. POST `/api/v1/budget-request`
Allows supervisors to submit budget requests via API.

---

## ⚙️ Setup Instructions

```bash
# Clone repository
git clone [https://github.com/manzhaus/PTS-Maintenance]

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env

# Run migrations and seed data
php artisan migrate --seed

# Build frontend assets
npm run build

# Start development server
php artisan serve

## 📝 Assumptions & Considerations

- Contextual Filtering: Supervisors cannot view data from other PTS locations.
- File Storage: Uses Laravel local disk for MVP; scalable to AWS S3.
- Scalability: Designed with modular structure for future expansion.

---

## 🚧 Phase 2 Roadmap

- Automated Excel export feature  
- Real-time notification system for budget approvals  
- Advanced analytics dashboard

---

## 📌 Notes

Before submitting or sharing:

- Ensure database is seeded:  
  ```bash
  php artisan migrate --seed
  Verify demo credentials are working
  Double-check Railway deployment is live
