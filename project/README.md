# Laravel Application Docker Image

This image contains the packaged Laravel application.

## Prerequisites

- Docker Installed
- Access to a generic web server (Nginx/Apache) and Database (MySQL/MariaDB)

## How to Run

### 1. Pull the Image

```bash
docker pull gedeadi32/sistem-informasi-idep:app-latest
```

### 2. Run with Docker Compose (Recommended)

Create a `docker-compose.yml` file:

```yaml
version: "3.8"
services:
    app:
        image: gedeadi32/sistem-informasi-idep:app-latest
        restart: always
        environment:
            APP_ENV: production
            APP_KEY: base64:... (Your Key)
            DB_HOST: db
            DB_DATABASE: laravel_db
            DB_USERNAME: laravel_user
            DB_PASSWORD: password
        networks:
            - app_net

    web:
        image: nginx:alpine
        ports:
            - "80:80"
        volumes:
            - ./nginx.conf:/etc/nginx/conf.d/default.conf
        depends_on:
            - app
        networks:
            - app_net

    db:
        image: mariadb:10.5
        environment:
            MYSQL_DATABASE: laravel_db
            MYSQL_USER: laravel_user
            MYSQL_PASSWORD: password
            MYSQL_ROOT_PASSWORD: root
        networks:
            - app_net

networks:
    app_net:
```

### 3. Run Manually

```bash
docker run -d --name idep_app \
  -e APP_ENV=production \
  -e DB_HOST=host.docker.internal \
  gedeadi32/sistem-informasi-idep:app-latest
```

## Mac Installation (Optimized for Apple Silicon)

This setup is optimized for Mac (especially M1/M2/M3 chips) using Alpine Linux containers for better performance.

### 1. Prerequisites
- Docker Desktop for Mac installed and running.
- Terminal access.

### 2. Configuration
If you haven't already, create the `.env` file:

```bash
cd project
cp .env.example.docker .env
```

### 3. Run with Optimized Docker Compose

Use the `docker-compose.mac.alpine.yml` configuration:

```bash
# Start containers (detached mode)
docker compose -f docker-compose.mac.alpine.yml up -d --build

# Stop containers
docker compose -f docker-compose.mac.alpine.yml down
```

### 4. Setup Application
The container will automatically handle the initial bootstrap (key generation, dependencies, linking storage) on the first run.

Monitor the logs to see when it's ready:

```bash
docker logs -f idep_app_mac
```

Wait until you see: `✅ Bootstrap finished, starting PHP-FPM`

### 5. Initialize Database
Run the migrations and seeders manually:

```bash
docker exec -it idep_app_mac php artisan migrate --seed
```

### 6. Access the Application
- **Web App**: [http://localhost](http://localhost)
- **PHPMyAdmin**: [http://localhost:8080](http://localhost:8080)

## Notes

- The image listens on port **9000** (PHP-FPM). You need a web server (like Nginx) to proxy requests to it.
- Ensure you mount or provide a `.env` file or environment variables.
