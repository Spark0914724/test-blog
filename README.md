# Simple Blog (PHP + Smarty + MySQL)

A minimal blog with categories and articles, built in **pure PHP 8** using **Smarty** and **MySQL**. No frameworks.

## Requirements

- PHP 8.1+
- MySQL 8.0 (or compatible)
- Composer

## Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Create database and tables

Create the `blog` database and run the schema:

```bash
mysql -u root -p < database/schema.sql
```

The schema creates the database and tables. If you use the default credentials (user `blog`, password `blog`), create the user first:

```sql
CREATE USER IF NOT EXISTS 'blog'@'%' IDENTIFIED BY 'blog';
GRANT ALL ON blog.* TO 'blog'@'%';
FLUSH PRIVILEGES;
```

Then run `database/schema.sql` (e.g. `SOURCE database/schema.sql;` in MySQL client).

### 3. Configure database (optional)

By default the app uses:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `blog`
- User: `blog`
- Password: `blog`

Override with environment variables: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.

### 4. Smarty directories

Ensure `templates_c` and `cache` exist and are writable:

```bash
mkdir -p templates_c cache
chmod 777 templates_c cache
```

On Windows, ensure the PHP process can write to these folders.

### 5. (Optional) Compile SCSS

Styles are built from SCSS. Pre-compiled CSS is in `public/assets/css/style.css`. To rebuild:

```bash
npm install
npm run build:css
```

To watch for changes: `npm run watch:css`.

### 6. Run the app

**Using PHP built-in server:**

```bash
php -S localhost:8080 -t public
```

Then open: http://localhost:8080

**Using Docker:**

```bash
docker-compose up --build
```

Then open: http://localhost:8080  
Database is created and migrated automatically. Run the seeder once: http://localhost:8080/index.php?action=seed

## Seeding

To fill the database with sample categories and articles:

```
http://localhost:8080/index.php?action=seed
```

This truncates and re-seeds `categories`, `articles`, and `article_categories`. Run once after setup.

## Project structure

```
├── config/
│   └── config.php          # App and DB config
├── database/
│   └── schema.sql          # MySQL schema
├── public/
│   ├── index.php           # Front controller
│   └── assets/
│       └── css/style.css   # Compiled styles
├── scss/                   # SCSS sources (optional build)
├── src/
│   ├── Config/
│   │   └── Database.php    # PDO connection
│   ├── Controller/         # Page handlers
│   └── Repository/         # Data access (Category, Article)
├── templates/              # Smarty templates
│   ├── layouts/
│   │   └── base.tpl
│   ├── home.tpl
│   ├── category.tpl
│   └── article.tpl
├── templates_c/             # Smarty compiled (writable)
├── cache/                  # Smarty cache (writable)
├── composer.json
├── docker-compose.yml
└── Dockerfile
```

## Pages

- **Home** (`?page=home`): Categories that have articles, each with 3 most recent posts and an “All Articles” link.
- **Category** (`?page=category&id=1`): Category title, description, article list with sorting (date/views) and pagination.
- **Article** (`?page=article&id=1`): Full article and a block of 3 similar articles (by shared category).

## Tech stack

- **PHP 8.1+**
- **Smarty** (template engine)
- **MySQL** (PDO)
- **SCSS** for styles (compiled to CSS)
- **Docker** (optional) for PHP + MySQL

No frameworks are used.
