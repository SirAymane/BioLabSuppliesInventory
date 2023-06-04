# BioLabSuppliesInventory

BioLabSuppliesInventory is a web-based inventory management system for biology lab supplies, implemented using PHP in MVC architecture and PDO for database interaction.

## Features

- Product Management: Add, edit, delete, and view biology lab supplies.
- Category Management: Organize products into categories.
- Warehouse Management: Keep track of inventory levels in different warehouses.
- User Management: User roles with different privileges.
- Dashboard: Overview of inventory status.

## Technologies Used

- PHP
- MVC Architecture
- PDO
- MySQL
- HTML/CSS
- Bootstrap

## Installation

### Step 1: Clone the repository

Run the following command in your terminal:
 
  git clone https://github.com/SirAymane/BioLabSuppliesInventory.git

### Step 2: Change the directory

Navigate into the cloned repository's directory:

  cd BioLabSuppliesInventory

### Step 3: Install a web server

Install a web server, for example, Apache, and ensure PHP is installed and running.

### Step 4: Setup a MySQL database

Run the following command in your terminal to login to MySQL (replace 'root' and 'password' with your MySQL credentials):

  mysql -u root -p


### Step 5: Run the database setup script

Run the following command in your terminal to create and populate the database with the credentials:

  mysql -u storeusr -p storepass < storedb.sql
  
### Step 6: Update configuration file

Update the `config/database.php` file with your database details.

### Step 7: Access the application

Now, you should be able to access the application by visiting 'localhost/BioLabSuppliesInventory' in your web browser (replace 'localhost' with your server's address if necessary).

## Contact

If you encounter any issues, feel free to contact me at aymaneelhanbali@gmail.com






