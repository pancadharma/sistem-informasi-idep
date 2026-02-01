#!/bin/sh
set -e

echo "🔹 Running Migrations..."
php artisan key:generate
# php artisan migrate

echo "🔹 Importing seed data..."
# Check if the file exists
if [ -f "database/idep/idepdev.sql" ]; then
    # Load .env variables if they don't exist in current environment
    if [ -f .env ]; then
        export $(grep -v '^#' .env | xargs)
    fi

    echo "🔹 Using DB User: ${DB_USERNAME}"
    
    # Use environment variables for credentials
    # Use sed to replace incompatible collation (MySQL 8.0 -> MariaDB)
    sed 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_ci/g' database/idep/idepdev.sql | mysql --skip-ssl -h db -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}"
    
    echo "✅ Data import completed successfully."
else
    echo "❌ Error: database/idep/idepdev.sql not found."
    exit 1
fi
