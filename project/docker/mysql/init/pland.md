1. Resetting the Database
   To apply the full root privileges for your database user, the database initialization scripts need to run. This only happens when the database volume is empty.

Question Answer: Yes, you need to delete the volume. The best way is:

# This stops containers and removes the volumes defined in the compose file

docker compose -f docker-compose.mac.alpine.yml down -v
Then start it back up:

docker compose -f docker-compose.mac.alpine.yml up -d
The
grant_privileges.sh
script will now automatically run and grant ALL PRIVILEGES to your user.

2. Importing Data
   I created a helper script
   import_data.sh
   inside the project.

To run migrations and import the data (
idepdev.sql
), run this command:

docker compose -f docker-compose.mac.alpine.yml exec app sh ./import_data.sh
This script will:

Run php artisan migrate
Import the
database/idep/idepdev.sql
file.
NOTE

I have automatically stripped the CREATE TABLE and ALTER TABLE statements from
idepdev.sql
so that it only inserts data, preventing conflicts with the Laravel migrations.
