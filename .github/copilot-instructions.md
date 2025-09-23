# Copilot Instructions

## Project Overview

This project is a simple e-commerce website for "Automotiva", an automotive aesthetics company. It's built with a classic LAMP stack: PHP for the backend, a MySQL database, and vanilla JavaScript and CSS for the frontend. The site is not built on a framework.

The main goal of the site is to showcase services, feature products for sale, and allow users to add products to a shopping cart.

## Key Files and Architecture

- **`index.php`**: The main landing page. It displays services, featured products, testimonials, and a contact form. It fetches data directly from the database.
- **`carrinho.php`**: This file serves two purposes: it displays the products available for purchase and also shows the current contents of the shopping cart.
- **`config.php`**: Contains the database connection credentials (`DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`). All files that need a database connection require this file.
- **`database.sql`**: The SQL script to set up the `automotiva_db` database and the `products` table. It also includes sample data.
- **`styles.css`**: The main stylesheet for the entire site. It uses CSS variables for theming.
- **`script.js`**: Contains the client-side JavaScript for the site, mainly for the navigation menu and some scroll effects.

## Backend (PHP)

- The backend is written in procedural PHP. There are no classes or objects.
- Database interactions are done using the `mysqli` extension in a procedural style.
- The shopping cart is managed using PHP sessions (`$_SESSION['cart']`).
- The `carrinho.php` file handles the logic for adding items to the cart and clearing it.
- All database queries are embedded directly within the PHP files (`index.php` and `carrinho.php`).

## Frontend (HTML/CSS/JS)

- The frontend is composed of semantic HTML5.
- CSS is organized with a mobile-first approach. Key design tokens (colors, fonts) are defined as CSS variables in `:root`.
- JavaScript is vanilla and is used for DOM manipulation, primarily for the responsive navigation menu and some simple scroll-based animations on the header.

## Developer Workflow

1.  **Setup**:
    *   You need a local server environment like XAMPP or WAMP.
    *   Place the project files in the `htdocs` (XAMPP) or `www` (WAMP) directory.
    *   Start the Apache and MySQL services.
    *   Import the `database.sql` file into your MySQL server (e.g., via phpMyAdmin). This will create the `automotiva_db` database and the `products` table.
    *   Make sure the database credentials in `config.php` match your local setup.

2.  **Running the site**:
    *   Access the site in your browser, typically at `http://localhost/automotiva/`.

## How to Help

- When adding new features, maintain the existing procedural style of the PHP code.
- For new UI components, follow the existing CSS patterns and use the defined CSS variables.
- Ensure any new database queries are properly prepared to prevent SQL injection, following the `mysqli_prepare` pattern seen in `carrinho.php`.
