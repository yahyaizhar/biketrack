##Mac Os, Ubuntu and windows users continue here:
- Create a database locally named `homestead` utf8_general_ci 
- Download composer https://getcomposer.org/download/
- Pull Laravel/php project from git provider.
- Rename `.env.example` file to `.env`inside your project root and fill the database information.
  (windows wont let you do it, so you have to open your console cd your project root directory and run `mv .env.example .env` )
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate`
- Run `php artisan db:seed` to run seeders, if any.
- Run `php artisan storage:link` to link storage.
- Run `mkdir storage/app`.
- Run `mkdir storage/framework`.
- Run `mkdir storage/framework/cache`.
- Run `mkdir storage/framework/cache/data`.
- Run `mkdir storage/framework/sessions`.
- Run `mkdir storage/framework/views`.

- Run `php artisan cache:clear`.
- Run `php artisan config:cache`.
