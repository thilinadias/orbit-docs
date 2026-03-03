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
   > **Note:** The `--build` flag is crucial for the first run to ensure the application image is built locally. 

3. **Access the Web Installer**
   Navigate to `http://localhost` (or your server's IP). OrbitDocs features a robust web-based installer that will:
   - Verify your environment requirements.
   - **Automatically create your database** if it doesn't exist.
   - Run system migrations and initial seeding (with 5-minute timeout protection).
   - Guide you through creating your first Admin account and Organization.

4. **Verify Deployment**
   If you encounter issues, you can follow the setup progress via logs:
   ```bash
   docker-compose logs -f app
   ```
   Once the installer finishes, you will be redirected to the login page.

## Access Application
- **URL:** `http://localhost`
- **Default Port:** 80 (standard HTTP)
