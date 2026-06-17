# RPSB Mini ERP - PTS Maintenance Cost Module

A functional MVP ERP module built for the **Pusat Timbang Sawit (PTS)** maintenance ecosystem. This system tracks staff costs, vehicle maintenance, and facility repairs while managing budget approval workflows between HQ and branch locations.

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

## 📷 Screenshots
Login Page
<img width="1322" height="910" alt="Screenshot 2026-06-17 130857" src="https://github.com/user-attachments/assets/6cbe372c-4151-483c-baaa-ab2c957f2d15" />

Admin and Supervisor Dashboard
<img width="1918" height="910" alt="Screenshot 2026-06-17 132638" src="https://github.com/user-attachments/assets/de63a277-0bcf-4617-b3c4-4ddc0a981ecb" />
<img width="1901" height="906" alt="Screenshot 2026-06-17 133829" src="https://github.com/user-attachments/assets/14352f2a-0607-485e-84a0-1fa9e42d1f00" />


Staff Management
<img width="1900" height="910" alt="Screenshot 2026-06-17 130957" src="https://github.com/user-attachments/assets/e9b4016c-75d3-4df4-bc4a-bdf61d5c84b7" />

Lorry Maintenance
<img width="1901" height="906" alt="Screenshot 2026-06-17 131202" src="https://github.com/user-attachments/assets/5f1c04e5-c02a-460f-aa9f-e49e172f8bcf" />

Flexible Asset Management
<img width="1898" height="907" alt="Screenshot 2026-06-17 131229" src="https://github.com/user-attachments/assets/10b9f6ed-235b-49a9-a5ad-19f0c451457f" />

Financial Governance & Budget Workflows
<img width="1918" height="910" alt="Screenshot 2026-06-17 132638" src="https://github.com/user-attachments/assets/67644013-bc5b-49e0-b922-fd58c697bff2" />
<img width="1918" height="911" alt="Screenshot 2026-06-17 132451" src="https://github.com/user-attachments/assets/3c77b516-076b-4044-9bd0-f13fb73663b8" />

Lorries Maintenance Record
<img width="1918" height="908" alt="Screenshot 2026-06-17 134334" src="https://github.com/user-attachments/assets/1150efa7-00e1-4083-b965-03188368d6c4" />


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
