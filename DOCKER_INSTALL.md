# OrbitDocs Docker Installation

## Requirements
- Docker Desktop or Docker Engine
- Docker Compose

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/orbitdocs.git
   cd orbitdocs
   ```

2. **Start Containers**
   ```bash
   docker-compose up -d --build
   ```

3. **Install Dependencies & Configure**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```

4. **Access Application**
   Visit `http://localhost:8000`
