# Contact Book Project

Welcome to the Contact Book project! This is a simple and efficient contact management system.

## Getting Started

### Prerequisites

- Docker and Docker Compose installed on your machine
- Environment variables properly configured

### Setup

1. Clone this repository
2. Create a `.env` file in the project root
3. Configure the necessary environment variables in the `.env` file

### Running the Project

To start the application in development, run:

```bash
docker compose -p contact-book-backend up -d --build
```
Now development application is running on port 8000 (http:localhost:8000/).

To start the application in production, run:

```bash
docker compose -f docker-compose.prod.yml -p contact-book-backend up -d --build
```
Now development application is running on port 80 (http:localhost/).

The application will be available at the configured port.

## Command to create fake data on database (run inside API docker container):

```bash
php artisan db:seed
```

### Environment Variables

Make sure to set up the following variables in your `.env` file:
- Database credentials
- API configurations
- Other required settings

```bash
APP_NAME=Contact-Book
APP_ENV=local
APP_KEY=base64:IFk6/BIx0fK+IVL2PId1qk96zKorPhNRVaWzz5zXozs=
APP_DEBUG=true
APP_URL=http://localhost/
APP_PORT=8000
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database
MEMCACHED_HOST=127.0.0.1
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
VITE_APP_NAME="${APP_NAME}"
```