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


## Docker Installation (Recommended)

1. **Clone the Repository**
   ```bash
   git clone https://github.com/thilinadias/orbitdocs.git
   cd orbitdocs
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   # Ensure APP_URL is set to your server IP (e.g., http://192.168.1.100)
   ```

3. **Build and Run Containers**
   *Note: On the first run, you MUST use `--build` to create the application image locally.*
   ```bash
   docker-compose up -d --build
   ```

4. **Access Application**
   Visit `http://localhost` (or your server IP) to access OrbitDocs.
