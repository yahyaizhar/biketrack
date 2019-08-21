##Biketrack Dashboard
#####run and follow these commands:
- Create a database locally named `homestead` utf8_general_ci 
- Download composer https://getcomposer.org/download/
- Pull Laravel/php project from git provider i.e. `git clone https://github.com/testingatswt/biketrack.git`.
- Rename `.env.example` file to `.env`inside your project root and fill the database information.
  (windows wont let you do it, so you have to open your console cd your project root directory and run `mv .env.example .env` )
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate`
- Run `php artisan db:seed` to run seeders, if any.
- Run `php artisan storage:link` to link storage.
- Run `mkdir storage/app&&mkdir storage/framework&&mkdir storage/framework/cache&&mkdir storage/framework/cache/data&&mkdir storage/framework/sessions&&mkdir storage/framework/views` to create cache storage.

- Run `php artisan cache:clear`.
- Run `php artisan config:cache` (this will faster the application).
