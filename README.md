🌾 HarvestGuard

A Decision Support System for Agricultural Monitoring and Inventory Management

📌 Overview

HarvestGuard is a comprehensive Decision Support System (DSS) designed for the Department of Agriculture – Office of Carmona. The system helps farmers and administrators efficiently monitor crop yields, detect plant diseases, and manage agricultural inventory and equipment.

By integrating image recognition, descriptive analytics, and real-time data tracking, HarvestGuard supports data-driven decision-making to improve agricultural productivity and resource management.

🎯 Objectives

Improve monitoring of crop yields and field conditions

Assist farmers in early detection of plant diseases

Streamline inventory and equipment management

Provide actionable insights through descriptive analytics

Enhance coordination between farmers and agricultural administrators

👥 Target Users

Farmers – for crop monitoring, disease detection, and supply requests

Administrators – for inventory control, analytics, and decision support

🛠️ Main Functions
1. 🌱 Crop & Disease Monitoring

Purpose: Helps farmers detect plant diseases early to prevent crop loss.
How it works:

Farmers upload or capture crop images using the system.

The system sends images to the ML API (Python/Flask).

Machine learning models analyze images and detect potential diseases.

The system provides:

Disease name

Effects on crops

Recommended solutions

Impact: Reduces crop damage and supports timely intervention.

2. 📊 Descriptive Analytics

Purpose: Provides actionable insights from collected data.
How it works:

The backend collects crop yield data, inventory usage, and request history.

Analytics engine generates:

Charts (e.g., crop trends, inventory status)

Summary statistics

Data is displayed in dashboards for both farmers and administrators.

Impact: Supports informed decision-making and efficient resource allocation.

3. 📦 Inventory Management

Purpose: Tracks agricultural supplies and equipment in real-time.
How it works:

Administrators and farmers can view stock levels.

Inventory is updated automatically when:

Items are borrowed

Supplies are used

Items are returned

Alerts notify administrators when stock is low.

Impact: Prevents stock shortages and ensures equipment availability.

4. 🧾 Equipment Borrowing System

Purpose: Allows farmers to request and borrow agricultural equipment.
How it works:

Farmers select equipment, quantity, and return date.

Requests are submitted through the system for approval.

System tracks borrowing history and current status.

Impact: Streamlines equipment allocation and ensures accountability.

5. 📄 Report Generation

Purpose: Generates formal reports for submission and record-keeping.
How it works:

Farmers can convert their crop monitoring and inventory reports into PDFs.

Reports include:

Crop status

Disease detection results

Borrowed equipment logs

PDF reports can be submitted to administrators directly.

Impact: Simplifies documentation and ensures transparency.

6. 🔔 Notifications & Status Updates

Purpose: Keeps users informed of actions and system events.
How it works:

Sends notifications for:

Borrow request approvals

Inventory updates

Disease detection results

Users see real-time status updates on dashboards.

Impact: Improves communication and reduces delays in decision-making.

🧰 Tech Stack
🌐 Frontend

HTML5, CSS3, JavaScript

Bootstrap / Tailwind CSS

jQuery & AJAX

⚙️ Backend

Laravel (PHP)

Composer

🧠 Machine Learning & Image Recognition

Python

Flask API

ML models for plant disease detection

Google Cloud Vision API (optional)

🗄️ Database

MySQL

📊 Analytics & Reporting

Chart.js / other chart libraries

PDF generation

🔐 Tools & Utilities

Git & GitHub

Postman

XAMPP / Laragon

🖌️ Design
🏗️ System Architecture

MVC (Model-View-Controller) using Laravel

Models → Database

Controllers → Business logic

Views → User interface

🎨 UI / UX Design

Responsive design for desktop, tablet, mobile

Clear call-to-action buttons and interactive dashboards

🔄 Workflow Design

Users interact with frontend

Backend API handles requests

Backend communicates with Database, ML API, and Analytics engine

Results displayed back on frontend

Users receive notifications and reports

🔄 System Flow Diagram (Text-Based)
                  ┌──────────────┐
          │   Farmer /   │
          │ Administrator│
          └─────┬────────┘
                │
                │ Interacts with
                ▼
         ┌───────────────┐
         │   Web Frontend │
         │ (UI / Forms / │
         │   Dashboard)  │
         └─────┬─────────┘
                │
                │ Sends Requests / Uploads Data
                ▼
         ┌───────────────┐
         │   Backend API  │
         │   (Laravel)    │
         └─────┬─────────┘
                │
   ┌────────────┼─────────────┐
   │            │             │
   ▼            ▼             ▼
┌────────┐  ┌──────────┐  ┌─────────────┐
│ Database│  │ Analytics│  │ ML / Image │
│  MySQL │  │  Engine  │  │ Recognition│
└────────┘  └──────────┘  │ (Python /  │
                          │   Flask)    │
                          └─────┬──────┘
                                │
                                ▼
                         ┌───────────────┐
                         │ Web Frontend  │
                         │ (Displays:    │
                         │ Crop Status,  │
                         │ Disease Info, │
                         │ Reports,      │
                         │ Inventory)    │
                         └─────┬─────────┘
                               │
                               ▼
                        ┌──────────────┐
                        │   Farmer /   │
                        │ Administrator│
                        └──────────────┘


🧪 Research & Evaluation

Evaluates effectiveness in crop monitoring, disease detection, inventory management
Measures user satisfaction: usefulness, functionality, reliability, ease of use


👨‍💻 Developer

Niel Joseph M. Samar
Bachelor of Science in Information Technology
Polytechnic University of the Philippines – San Pedro Campus

📄 License

Academic and research purposes. All rights reserved.