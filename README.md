# Kanojo

![GitHub](https://img.shields.io/github/license/kanojo-db/kanojo) [![Commitizen friendly](https://img.shields.io/badge/commitizen-friendly-brightgreen.svg)](http://commitizen.github.io/cz-cli/) ![GitHub branch checks state](https://img.shields.io/github/checks-status/kanojo-db/kanojo/main)

This repository contains the main code for Kanojo, comprising the API, backend and frontend of the database.

## Requirements

You will need to setup [PHP](https://www.php.net/downloads.php) >= 8.1 and [Node.js](https://nodejs.org/en/download/) >= 18 on your machine, as well as a [MariaDB](https://mariadb.org/) server running. You will also need [Composer](https://getcomposer.org/).

## Running a local server

**We recommand using [Laravel Sail](https://laravel.com/docs/10.x/sail) for development.**

Initialize the database using:

```bash
sail artisan migrate
```

Run the frontend development build using:

```
sail npm run dev
```
