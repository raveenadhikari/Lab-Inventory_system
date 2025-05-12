# UniLab Inventory: Lab Inventory Management System

[![PHP Version Requirement](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://www.php.net/)
[![Framework](https://img.shields.io/badge/Framework-CodeIgniter%204-orange.svg)](https://codeigniter.com)
<!-- Add other badges if you have them, e.g., build status, license -->

**UniLab Inventory** is a comprehensive Lab Inventory Management System built with CodeIgniter 4, designed for seamless implementation within university environments. It offers robust features to track, manage, and organize laboratory assets efficiently.

---

## ✨ Key Features

*   **Role-Based Permissions:** Granular control over what users can see and do, ensuring data integrity and security.
*   **University Email Authentication:** Secure login restricted to users with official university email addresses.
*   **Advanced Search Options:** Quickly find inventory items using multiple criteria and filters.
*   **QR Code Generation:** Automatically generate unique QR codes for each inventory item for easy identification.
*   **QR Code Scanning Integration:** Scan an item's QR code to directly open its detailed component page.
*   **CSV Data Import:** Easily populate and update inventory by uploading CSV files, streamlining data entry.
*   **User-Friendly Interface:** Intuitive design for ease of use by lab technicians, researchers, and administrators.
*   **Detailed Item Tracking:** Manage information such as item name, category, location, quantity, supplier, purchase date, and more.

---

## 📸 Screenshots

*(It's highly recommended to add a few screenshots of your application here. For example: Login page, Dashboard, Item list, QR code view, CSV upload interface)*

*   **Example:**
    `![Dashboard Screenshot](path/to/your/dashboard_screenshot.png)`
    `![Item Details Screenshot](path/to/your/item_details_screenshot.png)`

---

## 🛠️ Tech Stack

*   **Framework:** CodeIgniter 4
*   **Language:** PHP 8.1+
*   **Database:** MySQL (or your chosen database supported by CI4)
*   **Frontend:** HTML, CSS, JavaScript (mention any specific libraries like Bootstrap, jQuery if used)
*   **QR Code Generation:** chillerlan/php-qrcode


---

## 🚀 Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

*   PHP version 8.1 or higher with the following extensions:
    *   `intl`
    *   `mbstring`
    *   `json` (enabled by default)
    *   `mysqlnd` (if using MySQL)
    *   `libcurl` (if using `HTTP\CURLRequest`)
    *   `gd` or `imagick` (often required for QR code generation or image manipulation)
*   Composer (PHP package manager)
*   Git (Version control)
*   A web server (e.g., Apache, Nginx) or use PHP's built-in server via `php spark serve`
*   A database server (e.g., MySQL, PostgreSQL)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/your-repo-name.git
    cd your-repo-name
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Setup Environment Configuration:**
    *   Copy the `env` file to `.env`:
        ```bash
        cp env .env
        ```
    *   Open the `.env` file and configure it for your environment. Key settings include:
        *   `CI_ENVIRONMENT = development`
        *   `app.baseURL = 'http://localhost:8080'` (or your local development URL)
        *   Database settings (e.g., `database.default.hostname`, `database.default.database`, `database.default.username`, `database.default.password`)
        *   `app.universityEmailDomain = 'youruniversity.edu'` (Add this if you have a specific setting for university email validation)

4.  **Run Database Migrations:**
    This will create the necessary tables in your database.
    ```bash
    php spark migrate
    ```

5.  **(Optional) Run Database Seeders:**
    If you have seeders to populate initial data (e.g., default roles, admin user):
    ```bash
    php spark db:seed YourSeederName
    ```
    *(Replace `YourSeederName` with the actual name of your seeder class.)*

6.  **Set Folder Permissions:**
    Ensure the `writable` directory is writable by the web server.
    ```bash
    chmod -R 777 writable/
    ```
    *(For production, use more restrictive permissions and ensure the web server user owns the `writable` directory.)*

7.  **Web Server Configuration (Important Change with `index.php`):**
    The `index.php` file is no longer in the root of the project! It has been moved inside the `public` folder for better security. Configure your web server (Apache, Nginx) to point its document root to your project's `public` folder.

    *   **Apache:** You might use a `.htaccess` file in your project root (if `AllowOverride` is enabled) or configure a VirtualHost.
    *   **Nginx:** Configure the `root` directive in your server block.

    For development, you can use CodeIgniter's built-in server:

8.  **Run the Development Server:**
    ```bash
    php spark serve
    ```
    This will typically start the application at `http://localhost:8080`.

---

## 🔧 Configuration Details

Beyond the `.env` file, specific configurations might be found in:

*   `app/Config/`: Contains application-specific configuration files (Routes, Filters, Events, etc.).
*   **University Email Validation:** The system validates user emails against a specific university domain. Ensure `app.universityEmailDomain` in your `.env` file is set correctly (e.g., `app.universityEmailDomain = "university.ac.uk"`). If no such setting exists in code, describe how this is configured.
*   **Role Permissions:** Role definitions and their associated permissions are managed [describe where - e.g., "within the database" or "via an admin interface once logged in"].

---

## 💡 Usage

1.  **Access the application:** Open your configured `baseURL` (e.g., `http://localhost:8080`) in a web browser.
2.  **Login:** Use your university email address and password. New users may need to register (if self-registration is enabled) or be created by an administrator.
3.  **Navigate:** Use the sidebar/navigation menu to access different modules:
    *   Dashboard
    *   Inventory List (with advanced search)
    *   Add New Item
    *   Manage Categories/Locations (if applicable)
    *   User Management (for admins)
4.  **Adding Items:** Items can be added individually through a form or in bulk by uploading a CSV file.
5.  **QR Codes:** QR codes are automatically generated for new items. These can be printed and attached to physical assets. Scanning a QR code (e.g., with a mobile QR scanner app) will open a URL leading directly to that item's detail page in the system.

---

## 🤝 Contributing

Contributions are welcome! If you'd like to contribute, please follow these steps:

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

Please ensure your code adheres to the existing coding style and all tests pass.

---

## 📜 License

Distributed under the [Your License Name] License. See `LICENSE` file for more information.
*(If you don't have a LICENSE file, consider adding one, e.g., MIT License, Apache 2.0)*

---

## ℹ️ About CodeIgniter 4

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible, and secure. More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter. It has been built from the [development repository](https://github.com/codeigniter4/CodeIgniter4).

You can read the [user guide](https://codeigniter.com/user_guide/) corresponding to the latest version of the framework.

### Server Requirements for CodeIgniter 4

PHP version 8.1 or higher is required, with the following extensions installed:

*   [intl](http://php.net/manual/en/intl.requirements.php)
*   [mbstring](http://php.net/manual/en/mbstring.installation.php)

> \[!WARNING]
>
> * The end of life date for PHP 7.4 was November 28, 2022.
> * The end of life date for PHP 8.0 was November 26, 2023.
> * If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> * The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

*   `json` (enabled by default - don't turn it off)
*   `mysqlnd` if you plan to use MySQL
*   `libcurl` if you plan to use the `HTTP\CURLRequest` library

---

## 🙏 Acknowledgements

*   [CodeIgniter Foundation](https://codeigniter.com)
*   (Any other libraries or individuals you want to thank)

---

**Contact:** [Raveen Adhikari] - [raveenadhikari@gmail.com]

Project Link: [https://github.com/raveenadhikari/Lab-Inventory_system](https://github.com/raveenadhikari/Lab-Inventory_system)
