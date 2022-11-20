# Kanojo

This repository contains the main code for Kanojo, comprising the API, backend and frontend of the database.

## Requirements

You will need to setup [PHP](https://www.php.net/downloads.php) >= 8.0 and [Node.js](https://nodejs.org/en/download/) >= 18 on your machine, as well as a [MySQL](https://www.mysql.com/downloads/) server running. You will also need [Composer](https://getcomposer.org/).

## Running a local server

Initialize the database using:

```
php artisan migrate
```

Run the frontend development build using:

```
npm run dev
```

Then serve the Laravel app using:

```
php artisan serve
```


