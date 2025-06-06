#!/bin/sh
set -e

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
        composer install --prefer-dist --no-progress --no-interaction
    fi

    php bin/console importmap:install
    php bin/console asset-map:compile

    if grep -q ^DATABASE_URL= .env; then
        echo "Waiting for database to be ready..."
        ATTEMPTS_LEFT_TO_REACH_DATABASE=60
        
        # Print the DATABASE_URL for debugging (mask password)
        DB_URL=$(grep ^DATABASE_URL= .env | sed -E 's/(.+:\/\/.+:).+(@.+)/\1****\2/')
        echo "Using database connection: $DB_URL"
        
        until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
            if [ $? -eq 255 ]; then
                # If the Doctrine command exits with 255, an unrecoverable error occurred
                ATTEMPTS_LEFT_TO_REACH_DATABASE=0
                break
            fi
            sleep 2
            ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
            echo "Still waiting for database to be ready... $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
        done

        if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
            echo "The database is not up or not reachable:"
            echo "$DATABASE_ERROR"
            
            # Try to ping the database host to check network connectivity
            DB_HOST=$(grep ^DATABASE_URL= .env | sed -E 's/.*@([^:\/]+).*/\1/')
            echo "Attempting to ping database host: $DB_HOST"
            ping -c 3 $DB_HOST || true
            
            exit 1
        else
            echo "The database is now ready and reachable"
        fi

        if [ "$( find ./migrations -iname '*.php' -print -quit )" ]; then
            php bin/console doctrine:migrations:migrate --no-interaction
        fi
    fi

    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var || chmod -R 777 var
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var || true
fi

exec docker-php-entrypoint "$@"