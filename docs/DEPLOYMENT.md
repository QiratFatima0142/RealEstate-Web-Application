# Deployment guide

EstateEase ships two deliverables that you can host independently:

| Target                       | Folder      | Tech                    | Hosts                                          |
| ---------------------------- | ----------- | ----------------------- | ---------------------------------------------- |
| Static marketing + listings  | `public/`   | HTML / CSS / vanilla JS | **GitHub Pages** (primary), Netlify, Vercel    |
| Authenticated PHP portal     | `app/`      | PHP 8 + MySQL 8         | XAMPP, MAMP, Docker, Render, InfinityFree       |

---

## 1. GitHub Pages (static site)

GitHub Pages cannot execute PHP, so only `public/` is deployed there. The
workflow at [`.github/workflows/pages.yml`](../.github/workflows/pages.yml)
uploads the `public/` folder as a Pages artifact on every push to `main` and
deploys it automatically.

### One-time setup

1. Push this repo to GitHub.
2. Open **Settings &rarr; Pages**.
3. Under **Build and deployment &rarr; Source**, choose **GitHub Actions**.
4. (Optional) Configure a custom domain under **Custom domain**.

### Verify

After the first push:

* Open the **Actions** tab and watch `Deploy static site to GitHub Pages`
  run. It produces a `page_url` artifact in the deploy step's output.
* The default URL is `https://<user>.github.io/<repo>/`.
  Example: `https://QiratFatima0142.github.io/RealEstate-Web-Application/`.

### Local preview of the static site

```bash
cd public
python3 -m http.server 8000
# visit http://localhost:8000
```

Or, using Node:

```bash
npx --yes serve public
```

---

## 2. Running the PHP application locally

### Option A - XAMPP / MAMP

1. Install [XAMPP](https://www.apachefriends.org/) or
   [MAMP](https://www.mamp.info/).
2. Copy the `app/` folder into the Apache document root
   (`htdocs` on XAMPP, `/Applications/MAMP/htdocs` on MAMP). For example:

   ```
   htdocs/
    └── estateease/          <- contents of this repo's app/ folder
   ```

3. Start Apache and MySQL from the XAMPP control panel.
4. Open phpMyAdmin at `http://localhost/phpmyadmin` and run
   [`database/schema.sql`](../database/schema.sql) followed by
   [`database/seed.sql`](../database/seed.sql).
5. Visit `http://localhost/estateease/index.php`.
6. Sign in with the seeded account (password: `password`):

   | Email                       | Role          |
   | --------------------------- | ------------- |
   | `qirat@estateease.test`     | Demo agent    |

### Option B - PHP built-in server + MySQL

```bash
# apply the schema and seed (requires mysql on PATH)
mysql -uroot -p < database/schema.sql
mysql -uroot -p < database/seed.sql

# then run the PHP app
cd app
php -S localhost:8080
# visit http://localhost:8080/index.php
```

Set DB credentials via environment variables if they differ from the defaults
(`root` / empty / `localhost`):

```bash
export DB_HOST=localhost
export DB_PORT=3306
export DB_NAME=realstate
export DB_USER=root
export DB_PASS=secret
php -S localhost:8080
```

### Option C - Docker (zero-install)

Create a `docker-compose.yml` at the repo root (not committed by default):

```yaml
version: '3.9'
services:
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: realstate
    ports: ['3306:3306']
    volumes:
      - ./database/schema.sql:/docker-entrypoint-initdb.d/01-schema.sql:ro
      - ./database/seed.sql:/docker-entrypoint-initdb.d/02-seed.sql:ro

  web:
    image: php:8.2-apache
    depends_on: [db]
    environment:
      DB_HOST: db
      DB_USER: root
      DB_PASS: root
      DB_NAME: realstate
    ports: ['8080:80']
    volumes:
      - ./app:/var/www/html
```

Run:

```bash
docker compose up -d
# visit http://localhost:8080
```

---

## 3. Deploying the PHP app to a real server

For a free hosted demo of the full app:

1. **InfinityFree** (free PHP + MySQL hosting)
   * Create an account and new hosting plan.
   * Use the file manager to upload everything inside `app/` to `htdocs/`.
   * Create a MySQL database from the control panel and import
     `database/schema.sql` + `database/seed.sql` through phpMyAdmin.
   * Update the constants in `app/includes/config.php` (or set the
     `DB_*` environment variables in `.htaccess`) to match InfinityFree's
     database host name.

2. **Render.com** (free PHP docker template)
   * Fork this repo.
   * Create a new Web Service, point at the `app/` directory.
   * Add a MySQL add-on or connect an external MySQL (PlanetScale, Railway).
   * Add the same `DB_*` environment variables.

---

## 4. Environment variables

The PHP app reads the following variables, with XAMPP-friendly defaults:

| Variable   | Default      | Purpose                         |
| ---------- | ------------ | ------------------------------- |
| `DB_HOST`  | `localhost`  | MySQL host                      |
| `DB_PORT`  | `3306`       | MySQL port                      |
| `DB_NAME`  | `realstate`  | Database name                   |
| `DB_USER`  | `root`       | Database user                   |
| `DB_PASS`  | (empty)      | Database password               |
| `APP_ENV`  | `dev`        | Set to `production` to hide errors |

---

## 5. Troubleshooting

| Symptom                                              | Fix                                                              |
| ---------------------------------------------------- | ---------------------------------------------------------------- |
| "Database unavailable" on every page                 | MySQL is not running, or credentials in `config.php` are wrong.  |
| Photos on listing page show a placeholder            | The file was not found under `app/uploads/`. Re-upload via the form. |
| GitHub Pages returns 404 on `/`                      | Make sure Pages source is set to **GitHub Actions**, not a branch. |
| CI fails on `MySQL never came up`                    | Re-run the workflow; the service container is occasionally slow. |
| Login rejects seeded users                           | Make sure `database/seed.sql` was loaded after `schema.sql`.     |
