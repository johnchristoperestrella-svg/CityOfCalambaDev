# Deploying to Railway

Steps to deploy this PHP application to Railway using Docker:

1. Create a Railway project and connect your GitHub repo or use the Railway CLI.

2. Add a MySQL plugin (Resources → Add Plugin → MySQL). After adding, Railway will provide environment variables such as `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USERNAME`, `MYSQL_PASSWORD`, and `MYSQL_DATABASE`.

3. In Railway, set the following environment variables for the project (use Settings → Variables):

   - `DB_HOST` = value of `MYSQL_HOST` (or `MYSQL_HOST` directly)
   - `DB_PORT` = value of `MYSQL_PORT`
   - `DB_DATABASE` = value of `MYSQL_DATABASE`
   - `DB_USERNAME` = value of `MYSQL_USERNAME`
   - `DB_PASSWORD` = value of `MYSQL_PASSWORD`

   Note: This repository's `env()` helper will read `.env` if present, and will also fall back to system environment variables (so Railway env vars will work).

4. Deploy using Dockerfile (Railway will detect and build the Dockerfile). If using Railway CLI:

```bash
railway up
```

5. After deployment, run migrations / initial setup (one-time). Use Railway's Run command or SSH into a one-off shell and run:

```bash
php setup_database.php
php migrate.php
```

6. Verify the app is running by visiting the Railway project URL. If you need to change the app port, Railway maps the container port automatically.

7. Troubleshooting:
   - If the app cannot connect to the database, confirm `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_DATABASE` are set correctly and the MySQL plugin is attached to the project.
   - If uploads or storage require write access, ensure the container user has permissions for the target directories.
