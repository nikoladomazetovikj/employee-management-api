# Employee Management API (Server Side Only)

## Requirements:

- PHP 8.1 or above
- PostgresSQL 13 or above
- Mailhog (Test emails)
- Postman for APIs (you can find collection on the following link: https://github.com/nikoladomazetovikj/employee-management-postman)


## SetUp:

#### To setup the project please follow this instructions:

1. Clone this repo:

```
cd/destination_folder (on your local machine)
git clone {repo_url}
```

2. Install composer (latest version)

`https://getcomposer.org/download/`


3. Run this command:

```
composer install
```

4. Create a database on your local machine(PostgresSQL)

5. Rename `.env.example` file to `.env`

6. Open `.env` file and setup the following variables:

```
DB_DATABASE= {your database name}
DB_USERNAME= {db username}
DB_PASSWORD= {db password}
```

7. Generate key:

```
php artisan key:generate
```

8. In order to create db tables, run this command:

```
php artisan migrate --seed
```

9. Use `Mailhog` in order to send mails.

```https://github.com/mailhog/MailHog```
```angular2html
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```
Note: This is only relevant if you are using `MailHog` on your local server. Initially they are set in .env file

10. Run following command to generate secret key for your JWT Token.
```
php artisan jwt:secret 
```

11. Start Server:

```php artisan serve```

12. Follow the generated link and start using application (e.g. on localhost)

```
http://127.0.0.1:8000
```

