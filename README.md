## Name : Saja abdalhameed , Information technology

# Alzikrayat | Memories Platform

 **A custom-built, lightweight photo-sharing web application engineered with pure PHP, raw MVC pattern, and zero external backend frameworks.**


## Overview

**Alzikrayat** is a web platform designed to capture and preserve personal moments. Built from the ground up to demonstrate pure software engineering principles, it features a bespoke Regex routing engine, robust security mechanisms, and a responsive multi-view gallery interface.


## Key Capabilities

* **Secure Authentication Engine**
  * Custom Session Handling & Route Protection.
  * Password hashing powered by BCRYPT.
  * Cookie-based tracking for the last login timestamp (7-day expiry).

* **Interactive Gallery & Media Engine**
  * Dynamic layout switching between Grid View and List View without page reloads.
  * Ownership-bound photo deletion (verified at both Controller and SQL query levels).
  * Safe file upload pipeline with MIME/extension sanitization.

* **Custom Image Filter Engine (Novelty Feature)**
  * Server-side Grayscale & Sepia processing built directly on the PHP GD library — no external image API.
  * Automatic backup of the original, unfiltered file on first edit, with a one-click **Restore Original** action.

* **Engaging Social Features**
  * Threaded commenting system linked dynamically to uploaded memories.
  * Comments post instantly via AJAX (fetch) with no full-page reload, and gracefully fall back to a normal form submit if JavaScript is unavailable.
  * Adaptive Navbar reacting to user session status (Hi Name vs Please Login).

* **Triple-Layer Validation**
  * Multi-tier data integrity checks applied across HTML5, Client-side JavaScript, and Server-side PHP.


## Architectural Blueprint (MVC)

The project completely avoids third-party frameworks to showcase underlying core concepts:

```
alzikrayat/
├── alzikrayat.sql        # Database schema & initial structure
├── README.md             # Project documentation
├── config/                # Shared PDO Singleton Database Instance
├── core/                  # System Foundations (Base Model, Controller, Router)
├── controllers/           # Business Logic Controllers (Auth, Photo, Comment, Home)
├── models/                # Database Abstraction Models (User, Photo, Comment)
├── views/                 # Layout Templates & Pages
└── public/                # Web Root (index.php, CSS/JS assets, Uploads)
```

## Quick Start & Deployment

### Prerequisites
* XAMPP / WAMP with PHP 8.x and MySQL Server.

### Installation Steps
1. **Clone/Place Folder:** Move the `alzikrayat` folder to your local server directory (`C:/xampp/htdocs/alzikrayat`).
2. **Database Import:**
   * Open `http://localhost/phpmyadmin`.
   * Create a new database named `alzikrayat`.
   * Import the `alzikrayat.sql` file provided in the root directory.
3. **Run Application:**
   * Ensure Apache & MySQL are running in XAMPP.
   * Access the application via browser at:
     `http://localhost/alzikrayat/public/`


## Built With

* **Language:** PHP 8.x (Pure / Standard Library)
* **Database:** MySQL via PDO (Prepared Statements)
* **Frontend:** HTML5, Modern CSS3, JavaScript (ES6), Bootstrap 5
* **Architecture:** MVC Architecture + Singleton Pattern + Custom Regex Router