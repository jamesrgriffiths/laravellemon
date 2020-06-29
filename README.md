<p align="center"><img src="public/lemon-outline-name2.png" width="400"></p>

<p align="center">
<a href="https://opensource.org/licenses/"><img src="https://img.shields.io/badge/License-GPL%20v3-yellow.svg" alt="License"></a>
</p>

## About Laravel Lemon

Laravel Lemon is a scaffold built on top of the Laravel framework (7.1) with logging, user, and organizational management features. It uses the repository pattern, and implements VueJS. I built it as an easy starting point for some of my projects because I've found several of these features useful in the past.

## Setup - command line for new projects
You will need to setup your server for the new project, additionally if you wish to use the built in sub domain feature you will need to set up wildcard sub domains. The sub domain feature allows every organization created in your application to use their own sub domain of your site. This will allow new users to register under that organization and filter some views (such as users) to just those under the given organization. The remaining basic setup steps are below.

Clone the repository and set it to your repo.
```
git clone https://github.com/jamesrgriffiths/laravellemon.git <your-project>
cd <your-project>
git remote rm origin
git remote add origin <your-repository>
```

Install the composer and npm packages
```
composer install
npm install
```

Setup your environment
```
cp .env.example .env
php artisan key:generate
```
 - Now you will need to open the .env file and set your database connection settings (if it's a local mysql database you should just need the name, username, and password)
 - The system will also require email credentials unless you update some of the authentication logic.
 - You can also change the APP_NAME here.
 
 Setup and seed the database - seeding is neccessary for some preset variable features (make sure the database schema is created and properly referenced in the .env file)
 ```
 php artisan migrate --seed
 ```
 
 You may need to reload your server. When first visiting the site go to the registration link and fill out the form there (if you did not setup the email credentials you will get an error but still be registered). You then will need to go into your database under the users table and manually update the is_admin field under your account to 1. This will give you all the access you need but you will still be blocked from accessing most functionality until your email is verified. So either verify your email through the email that was sent to you, manually enter a date in the email_verified_at column in the database, or adjust the requirements in App/Repositories/Classes/VariableRepository.php the function getLoggedInUserRoutes($user_id).
