# BioLabSuppliesInventory

## Overview
BioLabSuppliesInventory is a web-based inventory management system designed specifically for managing biology lab supplies. The application is implemented using PHP, adhering to the MVC (Model-View-Controller) architecture, and utilizes PDO (PHP Data Objects) for database interactions.

## Features
- **User Authentication**: Secure login and user management.
- **Product Management**: Add, edit, remove, and view lab supplies.
- **Order Management**: Create, manage, and view orders.
- **Responsive Design**: User-friendly interface with CSS styling.

## Requirements
- PHP 7.4 or higher
- Composer
- MySQL or MariaDB
- Web server (e.g., Apache, Nginx)

## Installation
1. **Clone the repository**:
   ```sh
   git clone https://github.com/yourusername/BioLabSuppliesInventory.git
   cd BioLabSuppliesInventory
   ```

2. **Install dependencies**:
   ```sh
    composer install
    ```

3. **Set up the database**:

- Create a new MySQL database.
- Import the SQL schema:
   ```sh
   mysql -u yourusername -p yourpassword yourdatabase < sql/ecommerce.sql
   ```

4. **Configure the application**:

- Copy config.example.php to config.php and update the database credentials and other configuration settings.

5. **Run the application**:

Start your web server and navigate to the application's root directory.