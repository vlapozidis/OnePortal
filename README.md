<table>
<tr>
<td width="60%">

# Classter Employee Portal

A modern internal employee management portal built with Laravel and Blade, designed to simplify employee administration, leave management, workforce visibility, and internal operations.

### Features

- Employee Dashboard
- Leave Management
- Employee Directory
- Workforce Visibility
- Admin Panel
- Profile Management

</td>

<td width="40%">

<img src="https://github.com/user-attachments/assets/a7803eaa-f837-4fe5-b2c3-6748eee728fb" width="350">

</td>
</tr>
</table>

---

## Overview

Classter Employee Portal is a centralized platform that allows employees and administrators to manage daily workforce operations through a clean and secure interface.

The application provides visibility into employee availability, leave requests, work locations, and organizational information while maintaining a simple and intuitive user experience.

Built with Laravel and Blade, the platform follows a lightweight and maintainable architecture suitable for internal company environments.

---

## Key Features

### Employee Dashboard

Employees have access to a personalized dashboard containing important information at a glance.

Features include:

* Current leave balance
* Total leave days used
* Pending leave requests
* Department information
* Employment information
* Quick navigation to portal services



---

### Employee Directory

The employee directory provides a complete overview of the organization's workforce.

Employees can:

* View all employees
* Search employees
* Filter by department
* View work status
* View work location

Information displayed includes:

* Employee name
* Department
* Work mode
* Availability status


---

### Leave Management

The portal includes a complete leave request workflow.

Employees can:

* Submit leave requests
* Select leave dates
* Choose department
* Provide leave reason
* View leave history
* Track approval status

Leave request statuses:

* Pending
* Approved
* Rejected


---

### Workforce Visibility

Employees can quickly see who is available throughout the organization.

Workforce statuses include:

* Remote
* On Site
* On Leave

This helps teams coordinate more efficiently and improves organizational visibility.

## Workforce Visibility Preview 




<img width="1869" height="882" alt="Screenshot_5" src="https://github.com/user-attachments/assets/fb3cfc7e-f452-4115-b7e1-13ad086d2cad" />


---

### User Profile Management

Every employee has access to a personal profile section.

Users can:

* Update profile information
* Upload profile photo
* Manage personal details
* Maintain account information

## User Profile Management Preview
<img width="1868" height="904" alt="Screenshot_7" src="https://github.com/user-attachments/assets/d699eb62-7670-4c6a-a370-6d42787803b0" />


---

## Administrative Features

The portal uses a simple role system.

### Roles

#### User

Standard employee access.

Permissions:

* View dashboard
* Submit leave requests
* View leave history
* Access employee directory
* Manage profile settings

---

#### Admin

Full administrative access.

Permissions:

* Manage employees
* View all leave requests
* Approve leave requests
* Reject leave requests
* Access organization-wide information
* Manage departments
* Monitor workforce availability
* Access administrative tools

---

## Dashboard Preview


<img width="1886" height="717" alt="Screenshot_1" src="https://github.com/user-attachments/assets/18b29d83-ef62-4bee-8169-317e90d412d4" />

---

## Employee Directory Preview


<img width="1872" height="695" alt="Screenshot_2" src="https://github.com/user-attachments/assets/a54ec171-844f-48be-a61c-b39dca59766b" />

---

## Leave Requests Preview

<img width="1874" height="695" alt="Screenshot_4" src="https://github.com/user-attachments/assets/e19b20a4-d669-4ff7-a93a-cb35f9cf6664" />


---

## Admin Panel Preview

<img width="1866" height="857" alt="Screenshot_8" src="https://github.com/user-attachments/assets/8968917b-2c2d-487c-8cad-5a08985f65f5" />

---

## Technology Stack

### Backend

* Laravel
* PHP
* SQLite (Development)
* Laravel Herd

### Frontend

* Blade
* Tailwind CSS

### Authentication

* Laravel Breeze
* Microsoft Entra ID for employee accounts

## Login Page

<img width="1617" height="810" alt="Screenshot_10" src="https://github.com/user-attachments/assets/c7c83ccc-84bf-41a4-997f-c72850a333af" />



### Charts & Analytics

* Chart.js

## Charts & Analytics Preview
<img width="1869" height="887" alt="Screenshot_6" src="https://github.com/user-attachments/assets/6c39aae8-0dc6-485a-850f-8444d4c96409" />

---

## Project Structure

```text
app/
├── Models
├── Http
│   └── Controllers
├── Services

resources/
├── views
│   ├── dashboard
│   ├── employees
│   ├── leaves
│   ├── profile
│   └── admin

database/
├── migrations
├── factories
└── seeders
```

---

## Installation

### Clone Repository

```bash
git clone https://github.com/yourusername/ClassterEmployeePortal.git

cd ClassterEmployeePortal
```

### Install Dependencies

```bash
composer install

npm install
```

### Environment Configuration

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### Configure Database

Create an SQLite database file:

```bash
touch database/database.sqlite
```

Update your `.env` file:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Run Migrations

```bash
php artisan migrate
```

### Build Frontend Assets

```bash
npm run build
```

### Running the Application

#### Option 1: Laravel Herd (Recommended)

This project was primarily developed and tested using Laravel Herd.

If you have Herd installed:

1. Add the project to Herd.
2. Secure the site if needed.
3. Open the generated local URL from the Herd dashboard.

Example:

```text
https://classteremployeeportal.test
```

#### Option 2: Laravel Development Server

Run:

```bash
php artisan serve
```

The application will be available at:

```text
http://localhost:8000
```



## While any standard Laravel environment is supported, Laravel Herd is the recommended setup for local development.


## Future Enhancements

Planned features include:
 
* Email Notifications
* Leave Reports
* Department Statistics
* Export Functionality
* Audit Logs
* Team Calendar

---

## Security

Sensitive information such as credentials, API keys, and secrets are never stored in the repository.

Environment-specific configuration is managed through `.env` files.

---

## License

© 2026 Classter. All rights reserved.

This repository contains proprietary software developed for Classter's internal use.

The contents of this repository, including source code, documentation, and assets, are confidential and may not be reproduced, distributed, modified, or disclosed without prior written permission from Classter.

---

## Author

Developed for Classter's internal workforce management platform.
Designed to streamline employee management, leave requests, workforce visibility, and administrative operations.
