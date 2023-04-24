![Kanojo](https://raw.githubusercontent.com/kanojo-db/kanojo/main/public/images/logo-dark.svg#gh-dark-mode-only)![Kanojo](https://raw.githubusercontent.com/kanojo-db/kanojo/main/public/images/logo-light.svg#gh-light-mode-only)

# Kanojo

![GitHub](https://img.shields.io/github/license/kanojo-db/kanojo) [![Commitizen friendly](https://img.shields.io/badge/commitizen-friendly-brightgreen.svg)](http://commitizen.github.io/cz-cli/) ![GitHub branch checks state](https://img.shields.io/github/checks-status/kanojo-db/kanojo/main) ![Codecov](https://img.shields.io/codecov/c/gh/kanojo-db/kanojo?token=4P6GCNEJB3) [![Translation status](https://hosted.weblate.org/widgets/kanojo/-/website/svg-badge.svg)](https://hosted.weblate.org/engage/kanojo/) ![OSSF-Scorecard Score](https://img.shields.io/ossf-scorecard/github.com/kanojo-db/kanojo?label=ossf%20score) [![OpenSSF Best Practices](https://bestpractices.coreinfrastructure.org/projects/7200/badge)](https://bestpractices.coreinfrastructure.org/projects/7200) ![Discord](https://img.shields.io/discord/1065258808642912376)

This repository contains the main code for Kanojo, comprising the API, backend and frontend of the database.

## Contribute

If you are interested in contributing to the project, whether you are a developer or not, read through [CONTRIBUTING.md](https://github.com/kanojo-db/kanojo/blob/main/CONTRIBUTING.md).

Join on [our Discord]() to participate on discussions about features, bugs, translations or content moderation.

## Requirements

We use [Laravel Sail]() for development.

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
