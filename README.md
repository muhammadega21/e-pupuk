# e-pupuk Project Setup Guide

-   Clone the repository:

```bash
git clone https://github.com/muhammadega21/e-pupuk
```

-   Navigate to project directory:

```bash
cd e-pupuk
```

-   Install dependencies:

```bash
composer install
```

-   Open in your preferred editor (VS Code example):

```bash
code .
```

-   Duplicate file ".env-example" and rename it to ".env"
-   Generate application key:

```bash
php artisan key:generate
```

-   Configure database connection in .env:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_pupuk
DB_USERNAME=root
DB_PASSWORD=
```

-   Configure FILESYSTEM_DISK from local to public in .env:

```bash
FILESYSTEM_DISK=public
```

-   Run migrations with seeders:

```bash
php artisan migrate --seed
```

-   Create storage link:

```bash
php artisan storage:link
```

-   Start development server:

```bash
php artisan serve
```
