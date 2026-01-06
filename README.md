🌾 HarvestGuard

A Decision Support System for Agricultural Monitoring and Inventory Management

📌 Overview

HarvestGuard is a decision support system (DSS) designed for the Department of Agriculture – Office of Carmona, aimed at:

Monitoring crop yields

Detecting plant diseases

Managing agricultural inventory and equipment

It integrates machine learning, descriptive analytics, and real-time tracking to support data-driven decision-making and efficient resource management.

🎯 Objectives
Objective	Description
Crop Monitoring	Improve monitoring of crop yields and field conditions
Disease Detection	Early detection of plant diseases
Inventory Management	Streamline equipment and supply management
Data Analytics	Generate actionable insights for decision-making
User Coordination	Enhance collaboration between farmers and administrators
👥 Target Users

Farmers – Monitor crops, detect diseases, request equipment

Administrators – Manage inventory, review analytics, approve requests

🛠️ Main Functions
1️⃣ 🌱 Crop & Disease Monitoring

Upload or capture crop images

ML model analyzes images for diseases

Returns:

Disease name

Effects on crops

Recommended solutions

Impact: Prevents crop loss and enables timely interventions.

2️⃣ 📊 Descriptive Analytics

Collects crop yield, inventory, and request data

Generates charts & summaries

Dashboard visualization for farmers and admins

Impact: Data-driven decision-making and efficient resource allocation.

3️⃣ 📦 Inventory Management

Real-time tracking of supplies and equipment

Updates automatically with usage and returns

Alerts when stock is low

Impact: Prevents shortages and ensures availability.

4️⃣ 🧾 Equipment Borrowing System

Submit borrow requests with quantity & return date

Tracks borrowing status and history

Impact: Streamlines equipment allocation and accountability.

5️⃣ 📄 Report Generation

Convert monitoring and inventory reports to PDF

Submit reports to administrators

Impact: Simplifies documentation and ensures transparency.

6️⃣ 🔔 Notifications & Status Updates

Real-time alerts for:

Request approvals

Inventory updates

Disease detection results

Impact: Improves communication and reduces delays.

🖌️ Design & Architecture
🏗️ System Architecture

MVC (Model-View-Controller) using Laravel

Models → Database

Controllers → Business Logic

Views → Frontend/UI

🎨 UI / UX

Responsive design (desktop, tablet, mobile)

Interactive dashboards and clear call-to-action buttons

Mobile bottom navigation for key actions

🔄 Workflow
Farmer/Admin → Frontend → Backend API (Laravel) → Database / ML / Analytics → Frontend → Notifications & Reports

🔄 System Flow Diagram
          ┌──────────────┐
          │   Farmer /   │
          │ Administrator│
          └─────┬────────┘
                │
                ▼
         ┌───────────────┐
         │   Web Frontend │
         │ (UI / Dashboard) │
         └─────┬─────────┘
                │
                ▼
         ┌───────────────┐
         │ Backend API    │
         │ (Laravel)      │
         └─────┬─────────┘
   ┌────────────┼─────────────┐
   ▼            ▼             ▼
 Database      Analytics    ML / Image
 (MySQL)      Engine        Recognition
                        (Python / Flask)

🧰 Tech Stack
Layer	Technology
Frontend	HTML5, CSS3, JavaScript, Bootstrap/Tailwind, jQuery, AJAX
Backend	Laravel (PHP), Composer
Machine Learning	Python, Flask, ML Models, Google Cloud Vision API (optional)
Database	MySQL
Analytics & Reporting	Chart.js, PDF Generation
Tools	Git & GitHub, Postman, XAMPP / Laragon
🧪 Research & Evaluation

Effectiveness in crop monitoring & disease detection

Inventory management efficiency

User satisfaction: usefulness, functionality, reliability, ease of use

🚀 Future Enhancements

Predictive crop yield analytics

Mobile application support

Offline functionality for remote areas

SMS-based notifications

Expanded ML disease dataset

👨‍💻 Developer

Niel Joseph M. Samar
BSc Information Technology
Polytechnic University of the Philippines – San Pedro Campus

📄 License

Academic and research purposes. All rights reserved.