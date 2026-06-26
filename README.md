📚 Library Management System
A professional, full-stack web application designed for efficient library administration. Built with native PHP and MySQL, this system bridges the gap between users and administrators, providing a seamless interface for book management, borrowing processes, and secure authentication.

📸 Screenshots

📌 Modules & Features

1. User Interface

Book Discovery: Advanced search functionality by title, author, or category.

Borrowing System: Real-time availability tracking and personal "My Loans" dashboard.

Authentication: Secure user registration and login system with session management.

2. Administration Panel

Book Management (CRUD): Add, update, and delete book entries with full metadata control.

User Oversight: View, edit, and manage user accounts and system roles.

Role-Based Access Control (RBAC): Restrict sensitive administrative functions to authorized staff only.

✨ Key Technical Highlights

Secure Database Interfacing: Implementation of PDO with Prepared Statements to ensure robust protection against SQL Injection vulnerabilities.

Modularity: Professional code architecture separating business logic (includes/, admin/) from the presentation layer.

Responsive Design: Mobile-first architecture using Bootstrap 4.6 for a consistent experience across all devices.

Error Handling: Graceful error reporting and conditional UI rendering based on session states.

🛠️ Technologies Used

Backend: PHP (Native), PDO (PHP Data Objects)

Frontend: HTML5, CSS3, Bootstrap 4.6, FontAwesome

Database: MySQL

Architecture: MVC-inspired folder structure for clean maintainability.

🚀 How to Run

Clone the repository:

Bash
git clone https://github.com/your-username/Library-Management-System.git
Setup Server: Place the project files into your local server directory (e.g., htdocs for XAMPP).

Database Import: Import the provided SQL database schema into your MySQL/MariaDB server.

Configure: Update baglan.php with your local database credentials (username/password).

Launch: Access the platform via your browser: http://localhost/Library-Management-System/index.php.
