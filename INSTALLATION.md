# OrbitDocs Installation Guide (XAMPP / Local)

## Requirements
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/orbitdocs.git
   cd orbitdocs
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   # Update DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env
   ```

3. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Generate Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seed**
   ```bash
   php artisan migrate --seed
   ```

6. **Serve Application**
   ```bash
   npm run dev
   # In a separate terminal
   php artisan serve
   ```

Visit `http://localhost:8000` to access OrbitDocs.
