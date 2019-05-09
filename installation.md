#Installation Instructions#

**Note:We assume that you want to run it on development machine**

- We assume that you already have PHP 7.2.x(or at least 7.1.x),Mysql,Apache installed on your PC.

- Create database with whatever name you want to keep, say "sis".  We need this name and password with username for updating Laravel environment file later on.

- Clone repository in your local folder from https://github.com/prakashinfotech/sis-code-challenge

- You can perform any of below 2 options whichever is convenient to you

   1.Download attached .env file  and put it into document root folder(We have used deafault sample file for now so there may be   many unnecessary settings in it but it does not harm anything so keep it for now)
     OR
   2.Rename env.example file to .env file

- Replace all database related configurations that starts with DB_* in your .env file with your own local server values

- Replace all mail related configurations that starts with MAIL_* in your .env file with your own local server values.Please put your SMTP configurations otherwise registration may not work properly and you will get error.

- Open cmd/terminal and go to document root by running "cd DOCUMENT-ROOT"

- Run "composer install"

- Run "npm install" from command line after reaching to document root

- Run "php artisan key:generate" - it will update application key in your environment file

- Run "php artisan migrate"

- Run "php artisan db:seed"

- Run "php artisan config:cache" to clear and reload configurations changes

- Run "php artisan serve" - it will run application on http://127.0.0.1:8000

- login as admin using username "admin@admin.com" and password "admin123"

- you can register yourself as new employee by clciking on register button

##**Salient Features of our build**##
- App is supporting localization and internalization(i18n).App is developed using ,ulti-lingual feature in mind.
- Used laravel-mix for css,js minification,versioning (https://laravel.com/docs/5.8/mix)
- Used separate request objects to validate forms wherever possible,it makes changes in validation easier and separate from actual business logic
- Used laravel eloquent ORM model throughout the application (https://laravel.com/docs/5.8/eloquent)
- Used Bootstrap 4 for responsive design (https://getbootstrap.com/)
- Used datatables to manage paginated,ajax data across application (https://datatables.net/)
- Used spatie for authentication and authorization(https://github.com/spatie/laravel-permission)
- Used phpcs for code quality(see phpcs.xml)
- Verification of email after regitration is must to proceed inside
- We have used camelCase for variable naming convention
- When you are building application your app should use UTC/GMT time across application and database we are using it
- We have loaded js/css files only on page where it is required(for e.g we have loaded datatable js and css only on listing pages)

##**Assumptions**##

- When we import sample file we assume that all employees will be present in the system and if there are any new employees in the sample file we will reject import

- If you want to create new admin you can create it from database directly, run below query to do it

UPDATE `sis`.`model_has_roles` SET `role_id`='1' WHERE  `role_id`=2 AND `model_id`={your_new_admin_user_id} AND `model_type`='App\\User';


##Check Roles and Responsibilities##

- As an admin you will be able to see all user's monthly expense but as an employee you would be able to access only your own expense

##Missing Features/Suggestions##

- No edit/delete for now for employees expenses
- Admin User Management Screen

Please contact maulik.shah@prakashinfotech.com if you are having any issues to run project.

