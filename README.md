# FleetGo

## Project Overview
FleetGo is a modern, responsive web application designed to streamline and digitize fleet management and driver dispatch operations. Built to replace legacy manual logging systems, this application provides an intuitive, mobile-first interface for drivers to log multi-stop trips, while offering managers and administrators robust tools for fleet tracking, driver availability rotation, and comprehensive data reporting.

## Key Features

### 1. Role-Based Access Control (RBAC)
The system operates on a secure three-tier architecture:
*   **Driver:** Can initiate trips, log multiple sequential stops, and conclude trips. Operates within a mobile-optimized view.
*   **Manager:** Acts as the dispatch controller. Views fleet status, active drivers, offline queues, and generates operational reports.
*   **Administrator:** Possesses full system access, including CRUD operations for user management (provisioning new drivers/managers) and vehicle fleet management.

### 2. Intelligent Dispatch & FIFO Queue
To maximize driver efficiency and fairness, the system implements a **First-In-First-Out (FIFO) Rotation Queue**:
*   When a driver returns from a trip, they are automatically placed at the bottom of the dispatch queue.
*   The driver waiting the longest is mathematically sorted to the top of the "Available Drivers" list, ensuring a fair dispatch rotation without imposing hard system lockouts.

### 3. Fleet Tracking & Status Monitoring
*   **Real-time Status Pills:** Vehicles dynamically shift between `Available`, `On Trip`, and `Maintenance` states based on active system data.
*   **Live Dashboard Analytics:** Managers get an instant, high-level overview of active trips, inactive drivers, and vehicles currently deployed.

### 4. Comprehensive Reporting
*   **Dual-Format Exports:** Managers and Admins can query historical trip data by date ranges and export the results directly into **Excel (.xlsx)** for deep data manipulation or **PDF** for immediate presentation and printing.

### 5. Premium UI/UX Design
*   **Glassmorphism Identity:** The interface leverages modern glassmorphism design principles, offering semi-transparent frosted glass elements, smooth micro-interactions, and vibrant color coding.
*   **Mobile-First Strategy:** The entire application—including complex data tables and multi-step forms—is deeply optimized for iOS and Android devices, ensuring field drivers have a flawless experience on small screens.
*   **Custom Alpine.js Components:** Native browser defaults (like drop-downs and date pickers) have been replaced with highly polished, animated custom components.

---

## Technology Stack
This application was built utilizing a modern PHP/JavaScript stack optimized for rapid development, security, and scalability.

**Backend Architecture:**
*   **Framework:** Laravel 11 (PHP 8.2+)
*   **Database:** MySQL (Relational Database Management System)
*   **Authentication:** Laravel Session Authentication with Custom Middleware (`isAdmin`, `isManager`, `isDriver`)
*   **Reporting Libraries:** `maatwebsite/excel` (PhpSpreadsheet) for Excel generation and `barryvdh/laravel-dompdf` for PDF generation.

**Frontend Architecture:**
*   **Templating:** Laravel Blade
*   **Styling:** Tailwind CSS (Utility-first framework with custom glassmorphism extensions)
*   **Interactivity:** Alpine.js (Lightweight JavaScript framework for declarative DOM manipulation, modals, and custom dropdowns)
*   **Icons:** Ionicons
*   **Date Pickers:** Flatpickr

---

## System Requirements & Installation
To deploy this application on a local server or production environment, the following dependencies must be met:

### Prerequisites
*   PHP >= 8.2
*   Composer
*   MySQL >= 8.0
*   PHP Extensions required: `BCMath`, `Ctype`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`, `GD` (for Excel), `Zip` (for Excel).

### Setup Instructions
1. **Clone the repository:**
   ```bash
   git clone https://github.com/YourOrg/FleetGo.git
   cd FleetGo
   ```
2. **Install Composer Dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. **Environment Configuration:**
   Copy the example environment file and configure the database credentials.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Database Migration & Seeding:**
   Run the migrations to build the schema, and seed the database with initial administrative accounts.
   ```bash
   php artisan migrate --seed
   ```
5. **Serve the Application:**
   (For local development)
   ```bash
   php artisan serve
   ```

---

## Security & Privacy Considerations
*   **Password Hashing:** All user passwords are encrypted using the standard Laravel Bcrypt hashing algorithm.
*   **Route Protection:** Critical routes are guarded by custom Middleware layers preventing privilege escalation (e.g., Drivers cannot access Manager reports).
*   **CSRF Protection:** All form submissions are protected via Laravel's native CSRF token validation.
*   **SQL Injection Prevention:** All database queries utilize Eloquent ORM and prepared statements, ensuring safety against SQL injection attacks.

*Note: This repository is intended for portfolio demonstration purposes.*
