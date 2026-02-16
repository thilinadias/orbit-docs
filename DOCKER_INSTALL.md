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

3. **Wait for Initialization**
   The first run will take a few minutes to install dependencies (PHP & Node.js), run migrations, and build assets.
   You can verify it's working by checking the logs:
   ```bash
   docker-compose logs -f app
   ```
   Once you see **"OrbitDocs is ready"**, the app is live.

4. **Access Application**
   Visit `http://localhost:8000`
