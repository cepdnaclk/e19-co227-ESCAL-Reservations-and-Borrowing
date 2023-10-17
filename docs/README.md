---
layout: home
permalink: index.html

repository-name: e19-co227-ESCAL-Reservations-and-Borrowing
title: ESCAL Reservations and Borrowing
---

[comment]: # "This is the standard layout for the project, but you can clean this and use your own template"

# ESCAL Reservations and Borrowing

---

<!-- 
This is a sample image, to show how to add images to your page. To learn more options, please refer [this](https://projects.ce.pdn.ac.lk/docs/faq/how-to-add-an-image/)

![Sample Image](./images/sample.png)
 -->

## Team
-  E/19/240, Mendis B.M.S., [email](mailto:e19240@eng.pdn.ac.lk)
-  E/19/278, Perera A.P.T.T., [email](mailto:e19278@eng.pdn.ac.lk)
-  E/19/426, Weerasinghe P.M., [email](mailto:e19426@eng.pdn.ac.lk)
-  E/19/453, Withanaarachchi W.A.A.M.T., [email](mailto:e19453@eng.pdn.ac.lk)

## Table of Contents
1. [Introduction](#introduction)
2. [Other Sub Topics](#other-sub-topics)
3. [Links](#links)

---

## Introduction

Laravel Boilerplate provides you with a massive head start on any size web application. Out of the box it has features like a backend built on CoreUI with Spatie/Permission authorization. It has a frontend scaffold built on Bootstrap 4. Other features such as Two Factor Authentication, User/Role management, searchable/sortable tables built on my [Laravel Livewire tables plugin](https://github.com/rappasoft/laravel-livewire-tables), user impersonation, timezone support, multi-lingual support with 20+ built in languages, demo mode, and much more.

## Other Sub Topics

### Demo Credentials

**Admin:** admin@example.com  
**Password:** admin_user

**User:** user@example.com  
**Password:** regular_user

**User:** lecturer@example.com  
**Password:** lecturer_user

[Click here for the official documentation](http://laravel-boilerplate.com)

## Team of Developers

-   [Nuwan Jaliyagoda](http://github.com/NuwanJ)

### Sprint 2A

-   [Tharmapalan Thanujan](http://github.com/thanujan96)
-   [Madhushan Ramalingam](https://github.com/DrMadhushan)
-   [Thilini Madushani](http://github.com/Thilini98)

### Sprint 3A

-   [Ishan Fernando](https://github.com/ishanfdo18098)
-   [Adeepa Fernando](https://github.com/NipunFernando)
-   [Ridma Jayasundara ](https://github.com/ridmajayasundara)

### Sprint 3B

-   [Sadia Jameel](https://github.com/SaadiaJameel)
-   [Sakuni Nimnadi](https://github.com/SakuniJayasinghe)
-   [Thamish Wanduragala](https://github.com/Thamish99)

### Sprint 3C

-   [Karan R.](https://github.com/rasathuraikaran)
-   [Gowsigan A.](https://github.com/AnnalingamGowsigan)
-   [Muthuni De Alwis](https://github.com/muthuni-dealwis)

### Sprint 4A

-   [Withanaarachchi W.A.A.M.T](https://github.com/AkashMuthumal)
-   [Mendis B.M.S.]()
-   [A.P.T.T.Perera]()
-   [Weerasinghe P.M.](https://github.com/PubudU99)

## Useful Commands and Instructions

You need to install Wamp server and run it before following commands.
Please make sure you already created database user account.

#### Install Dependencies

```
// Install PHP dependencies
composer install

// If you received mmap() error, use this command
// php -d memory_limit=-1 /usr/local/bin/composer install

// Update PHP dependencies
composer update

// Install Node dependencies (development mode)
npm install
npm run dev
```

#### Prepare for the first run

```
// Prepare the public link for storage
php artisan storage:link

// Prepare the database
php artisan migrate

// Reset the database and seed the data
php artisan migrate:fresh --seed

```

#### Serve in the local environment

```
// Serve PHP web server
php artisan serve

// Serve PHP web server, in a specific IP & port
php artisan serve --host=0.0.0.0 --port=8000

// To work with Vue components
npm run watch
```

#### Run all above commands from bash script

```
// Enable execution of bash script (for Linux)
chmod +x Start.sh

// Run bash script
./Start.sh
```

#### Cache and optimization

```
// Remove dev dependencies
composer install --optimize-autoloader --no-dev

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Maintenance related commands

```
php artisan down --message="{Message}" --retry=60
php artisan up
```

#### Other useful instructions

```
// Create Model, Controller and Database Seeder
php artisan make:model {name} --migration --controller --seed

// Create a Email
php artisan make:mail -m

// Commandline interface for Database Operations
php artisan tinker

// Run the unit tests
php artisan test

// Run unit tests in parallel
php artisan test -p

```

#### Resource Routes

| Verb      | URI                             | Action  | Route Name             |
| :-------- | :------------------------------ | :------ | :--------------------- |
| GET       | /photos/{photo}/comments        | index   | photos.comments.index  |
| GET       | /photos/{photo}/comments/create | create  | photos.comments.create |
| POST      | /photos/{photo}/comments        | store   | photos.comments.store  |
| GET       | /comments/{comment}             | show    | comments.show          |
| GET       | /comments/{comment}/edit        | edit    | comments.edit          |
| PUT/PATCH | /comments/{comment}             | update  | comments.update        |
| DELETE    | /comments/{comment}             | destroy | comments.destroy       |

.....
![1](https://github.com/cepdnaclk/e19-co227-ESCAL-Reservations-and-Borrowing/assets/115542100/33bb3857-0efc-42dd-9e58-85e1f37eb391)
![2](https://github.com/cepdnaclk/e19-co227-ESCAL-Reservations-and-Borrowing/assets/115542100/9f560b5f-5548-42ac-8abc-966c59c88d08)
![3](https://github.com/cepdnaclk/e19-co227-ESCAL-Reservations-and-Borrowing/assets/115542100/b32f25f5-3e43-4fd7-bd29-ea4e2582f49d)

## Links

- [Project Repository](https://github.com/cepdnaclk/{{ page.repository-name }}){:target="_blank"}
- [Project Page](https://cepdnaclk.github.io/{{ page.repository-name}}){:target="_blank"}
- [Department of Computer Engineering](http://www.ce.pdn.ac.lk/)
- [University of Peradeniya](https://eng.pdn.ac.lk/)


[//]: # (Please refer this to learn more about Markdown syntax)
[//]: # (https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
