# Developer Assessment Task - Slipstream

## Overview
This project is a functional demonstration showcasing proficiency in Laravel 11, Vue.js, and MySQL. It implements a customer and contacts listing page with CRUD operations, including modals for creating and editing records.

## Features
### Customer Listing Page
- Summary table displaying customers
- Create button to add new customers
- Plain text search functionality
- Edit/Delete options for each row
- Clicking "Create" or "Edit" opens a modal
- Delete action prompts a confirmation modal

### Customer Detail Form (Modal)
- Save and Back buttons
- Fields:
  - Text field
  - Text area
  - Date picker
  - Dropdown (Category: Gold, Silver, Bronze)
- Contacts management (Create, Edit, Delete) within the customer modal

## Technologies Used
- **Backend**: Laravel 11
- **Frontend**: Vue.js, Inertia.js
- **Database**: MySQL

## Installation and Setup
### Prerequisites
Ensure you have the following installed on your system:
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or any compatible database

### Steps to Install and Run
1. **Clone the Repository**
   ```bash
   git clone https://github.com/IntelCoder0103/slipstream.git
   cd slipstream
   ```

2. **Install Backend Dependencies**
   ```bash
   composer install
   ```

3. **Set Up Environment Variables**
   Copy the `.env.example` file to `.env` and configure the database credentials:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database details:
   ```env
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

4. **Run Database Migrations and Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Start the Laravel Development Server**
   ```bash
   php artisan serve
   ```

7. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

8. **Run Vite for Frontend Compilation**
   ```bash
   npm run dev
   ```

9. **Access the Application**
   Open [http://localhost:8000](http://localhost:8000) in your browser.

## Web Routes
| Method | Route                                    | Description                         |
|--------|------------------------------------------|-------------------------------------|
| GET    | /customers?search=&category_id=          | Fetch all customers with filters    |
| POST   | /customers                               | Create a new customer               |
| PUT    | /customers/{id}                          | Update a customer                   |
| DELETE | /customers/{id}                          | Delete a customer                   |

## Testing

**Set Up Environment Variables**

Copy the `.env.example` file to `.env` and configure the database credentials:
```bash
cp .env.example .env.testing
```
Update `.env.testing` with your database details:
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

To run tests, use the following command:

```bash
php artisan test
```
This will execute all test cases to ensure application stability.

## Contact
For any questions regarding this assessment, feel free to contact:
**Lazar Popovic** - lazarpopovic13@outlook.com
