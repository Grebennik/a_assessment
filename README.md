## System Architecture

```plaintext
+------------------------+
|      Frontend          |
|   (Swagger UI, etc.)   |
+------------------------+
           |
           v
+------------------------+
|   Verification API     |
|      (Laravel)         |
|   - Controllers        |
|   - Services           |
|   - Strategies         |
+------------------------+
           |
           v
+------------------------+
|       Database         |
|   - Users              |
|   - Verifications      |
+------------------------+
```


# Verification API

This project is a Laravel-based API for verifying JSON files. The API allows users to upload and verify JSON files against specific criteria, such as valid recipients, issuers, and signatures.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Docker and Docker Compose (for containerized setup)
- Node.js and npm (for front-end assets)

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/Grebennik/a_assessment.git
   cd a_assessment
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Copy the Environment File**:
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Set Up Docker Containers**:
   Make sure Docker is running, then run:
   ```bash
   docker-compose up -d
   ```

6. **Run Migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Serve the Application**:
   If you are not using Docker for serving:
   ```bash
   php artisan serve
   ```
   Or, access the application via Docker at `http://localhost:8080`.

## Running Tests

1. **Run Feature and Unit Tests**:
   ```bash
   php artisan test
   ```

## API Documentation

You can view the API documentation using Swagger UI:

1. **Access the Swagger UI**:
   Navigate to `http://localhost:8080/swagger.html` in your browser.

2. **Endpoints**:
    - `POST /api/verify`: Upload a JSON file for verification.

## Additional Information

- **Adding New Verification Strategies**: Simply implement the `VerificationStrategyInterface` and bind the new strategy in the `AppServiceProvider`.
- **OpenAPI Specification**: Located in `public/docs/openapi.yaml`.

