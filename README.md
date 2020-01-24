# HRIS API

Backend API for HRIS web app

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. The project is built with the [Laravel Framework](https://laravel.com/docs/6.x/) so it's best to learn the basics of this framework before using this project.

## Prerequisites
* Composer >= 1.9.1  - For installing laravel and vendor packages

* Node JS >= 12.13.1 - For installing js libraries

* Laravel Framework >= 6.9.x  - PHP framework used for this project

* PHP >= 7.13.12 - Programming languaged used

* Git - For cloning project repository to local environment

* Insomnia - For testing project APIs

* XAMPP - Web server for hosting database and web applications

## Server Requirements
The Laravel framework has a few system requirements. To learn more about the requirements, go to laravel's installation documentation for [Server Requirements](https://laravel.com/docs/6.x/installation#server-requirements).

## Installation
Laravel utilizes Composer to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.

More details for [Laravel Installation](https://laravel.com/docs/6.x/installation#installing-laravel).

Once you have installed all the prerequisites, and have grasped the basics of the Laravel Framework, you may now start cloning the project to your local machine via git.

```
git clone https://github.com/kenneth-nava-bot/hris.git
cd hris
```

Install npm and composer modules.

```
npm install
composer install
```

Create your .env file by duplicating the contents of ".env.example" file, located in the root folder of the project, then save it as ".env".

For the setup of the .env file, first you want to generate your application key.
```
php artisan key:generate
```
The command will generate an APP_KEY inside your ".env" file.

Start your XAMPP server and create an empty database, then update the necessary database configuration in your ".env" file.

```
...

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

...
```

You can learn more about Laravel's database configuration in their [documentations](https://laravel.com/docs/6.x/database).

This app also features sending of emails. So you need to setup the configuration for the email. For testing and development purposes, you can use [Mailtrap](https://mailtrap.io/).

```
...

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

...
```

Other settings you need to configure in the ".env" file:

```
APP_URL= // This will be used as the API's base url
APP_UI_URL= // The url for the web app that will consume the APIs, or simply the frontend of the web app.
APP_DEFAULT_PASS= // Default password for seeded/default users
LOG_SLACK_WEBHOOK_URL= // Web hook url for slack logging
```

Now you need to migrate the database, run the seeder for testing purposes, and then get a new Passport ID and SECRET. You can do all these commands using an artisan command.

Make sure your XAMPP server is running.

```
php artisan run:dev
```

The terminal will display two Passport ID and Secret. You will the need the second one, which is the password grant credentials, and copy it to the following properties of the ".env" file.

```
PASSWORD_GRANT_ID=
PASSWORD_GRANT_SECRET=
```

You can learn more about Laravel Passport in their [documentations](https://laravel.com/docs/6.x/passport).

## Testing the APIs
Start your XAMPP server, and then open Insomnia. Download the export file of the available APIs in the app via git.

```
git clone https://github.com/kenneth-nava-bot/hris_routes.git
```

Import the file in Insomnia and you will now be able to test the backend APIs.
