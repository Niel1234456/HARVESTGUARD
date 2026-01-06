🌾 HARVESTGUARD
Decision Support System for Agricultural Monitoring and Inventory Management

📌 OVERVIEW
HarvestGuard is a decision support system (DSS) designed for the Department of Agriculture – Office of Carmona, aimed at:
Monitoring crop yields
Detecting plant diseases
Managing agricultural inventory and equipment
It integrates machine learning, descriptive analytics, and real-time tracking to support data-driven decision-making and efficient resource management.

🎯 OBJECTIVES
| Objective            | Description                                              |
| -------------------- | -------------------------------------------------------- |
| Crop Monitoring      | Improve monitoring of crop yields and field conditions   |
| Disease Detection    | Early detection of plant diseases                        |
| Inventory Management | Streamline equipment and supply management               |
| Data Analytics       | Generate actionable insights for decision-making         |
| User Coordination    | Enhance collaboration between farmers and administrators |

👥 TARGET USERS
| User Type      | Description                                          |
| -------------- | ---------------------------------------------------- |
| Farmers        | Monitor crops, detect diseases, request equipment    |
| Administrators | Manage inventory, review analytics, approve requests |

🛠️ MAIN FUNCTIONS
1️⃣ CROP & DISEASE MONITORING

Purpose: Detect plant diseases early to prevent crop loss
How it works:

Farmers upload or capture crop images.

ML model (Python/Flask API) analyzes images.

Returns:

Disease name

Effects on crops

Recommended solutions

Impact: Prevents crop damage and enables timely interventions.

2️⃣ DESCRIPTIVE ANALYTICS

Purpose: Provides actionable insights from data
How it works:

Collects crop yield, inventory, and request data.

Generates charts & summaries using analytics engine.

Dashboard displays trends for farmers and administrators.

Impact: Supports data-driven decision-making.

3️⃣ INVENTORY MANAGEMENT

Purpose: Track supplies and equipment in real-time
How it works:

Updates stock automatically for borrowed/used/returned items.

Alerts administrators when stock is low.

Impact: Prevents shortages and ensures availability.

4️⃣ EQUIPMENT BORROWING SYSTEM

Purpose: Request and borrow agricultural equipment
How it works:

Farmers submit requests with quantity and return date.

Backend tracks approval and history.

Impact: Streamlines equipment allocation and accountability.

5️⃣ REPORT GENERATION

Purpose: Generate formal reports
How it works:

Convert crop monitoring and inventory data to PDF

Reports include crop status, disease detection, borrowed equipment

Impact: Simplifies documentation and improves transparency.

6️⃣ NOTIFICATIONS & STATUS UPDATES

Purpose: Keep users informed in real-time
How it works:

Sends notifications for requests, inventory updates, and disease results.

Impact: Improves communication and reduces delays.

🖌️ DESIGN & ARCHITECTURE
SYSTEM ARCHITECTURE

MVC (Model-View-Controller) with Laravel

Models → Database

Controllers → Business Logic

Views → Frontend/UI

UI / UX DESIGN

Fully responsive (desktop, tablet, mobile)

Interactive dashboards with clear buttons and navigation

Mobile-friendly bottom navigation for key actions

WORKFLOW
Farmer/Admin → Frontend → Backend API (Laravel) → Database / ML / Analytics → Frontend → Notifications & Reports

🧰 TECH STACK
| Layer                 | Technology                                                   |
| --------------------- | ------------------------------------------------------------ |
| Frontend              | HTML5, CSS3, JavaScript, Bootstrap/Tailwind, jQuery, AJAX    |
| Backend               | Laravel (PHP), Composer                                      |
| Machine Learning      | Python, Flask, ML Models, Google Cloud Vision API (optional) |
| Database              | MySQL                                                        |
| Analytics & Reporting | Chart.js, PDF Generation                                     |
| Tools                 | Git & GitHub, Postman, XAMPP / Laragon                       |

🧪 RESEARCH & EVALUATION

Measures effectiveness in crop monitoring & disease detection

Evaluates inventory management efficiency

User satisfaction metrics: usefulness, functionality, reliability, ease of use


👨‍💻 DEVELOPER

Niel Joseph M. Samar
BSc Information Technology
Polytechnic University of the Philippines – San Pedro Campus

📄 LICENSE
Academic and research purposes. All rights reserved.