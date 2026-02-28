#!/bin/bash
set -e

echo "Granting all privileges to ${MYSQL_USER}..."

mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    GRANT ALL PRIVILEGES ON *.* TO '${MYSQL_USER}'@'%' WITH GRANT OPTION;
    FLUSH PRIVILEGES;
EOSQL

echo "Privileges granted."
