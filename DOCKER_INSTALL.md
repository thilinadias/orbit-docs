# OrbitDocs Docker Installation

## Requirements
- Docker Desktop or Docker Engine
- Docker Compose

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/thilinadias/orbit-docs.git
   cd orbit-docs
   ```

2. **Start Containers**
   ```bash
   docker-compose up -d --build
   ```
   > **Note:** The `--build` flag is crucial for the first run to ensure the application image is built locally. If you omit it, Docker may try to pull a non-existent image and fail.

3. **Install Dependencies & Configure**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan storage:link
   docker-compose exec app php artisan migrate --seed
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```

4. **Access Application**
   Visit `http://localhost:8000`
