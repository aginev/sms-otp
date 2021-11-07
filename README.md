# SMS OTP Mock

## Requirements
1. PHP8
2. Database - MariaDB 10.3 was used
4. Webserver - Tested at Nginx 1.18 only
3. Composer

## Installation & Configuration

1. Clone the repository
2. Run `composer install` to download all the dependencies. Currently, the project depends on just two packages. First for environment management and second one for request data validation.
3. Create database for the project.
4. Use `schema.sql` to populate all the necessary tables.
5. Copy `.env.example` to `.env` at the root of the project.
6. Fill required credentials for the database connection at the `.env` file.
7. Setup virtual host for the project (note that document root should point to `public` directory). Nginx example configuration as follows:

```nginx
server {
    listen 80;
    listen [::]:80;

    root /path-to-project-directory/public;

    index index.php;

    access_log off;
    error_log /path-to-project-directory/error.log;

    server_name sms-otp.test;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # pass PHP scripts to FastCGI server
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    }

    # deny access to .htaccess files, if Apache's document root
    # concurs with nginx's one
    location ~ /\.ht {
        deny all;
    }
}

```

8. Modify your host file to be able to use the test domain.

```
127.0.0.1   sms-otp.test
```

## Usage
1. `/` route will show a welcome page.
2. `/register` will show the user registration form.
3. After successful registration, you will be redirected to phone verification page (`/verify`).
4. While at `/verify`, wait 1 minute and refresh to view the extra button for sending new phone verification.
5. Submit verification form up to 3 time to get verification cool down message. Wait 1 minute to be able to use this feature again.
6. After successful verification you will be redirected to `/dashboard` which is just a page that shown that all went OK.

## Structure
- `/public` - The document root
- `/public/index.php` - Application boostrap, route setup, etc.
- `/src/Controllers` - Controllers directory. Controllers were made as a single action controller just to keep the as clean as possible.
- `/src/Core` - Application core modules like session, database, etc.
- `/src/Exceptions` - Application exceptions
- `/src/Models` - Model files.
- `/src/Repositories` - Where the actual database queries are executed.
- `/src/Services` - Service classes like Notification service, phone sanitizer and validator service, etc.
- `/src/Support` - Helper classes and files.
- `/src/View` - HTML templates.
