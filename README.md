<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Laravel TZ for GodLike

### How to install

This section provides instructions on how to install the Laravel project on a local environment or Docker.
1. Make sure you have PHP, Composer, and a database server (e.g., MySQL) installed on your local machine.

2. Clone the project repository from GitHub:

    ```shell
    git clone <repository_url>
    composer install
    docker-compose up -d
    ```
    After that you have started the project on your local machine. You can access the project by visiting `http://172.21.2.3` in your browser.

3. In container you need execute this commands for additional permissions for write logs and cache. 

    ```shell
    docker-compose exec php bash
    
    chmode 777 -R storage/logs
    chmode 777 -R storage/framework
    ```

4. Create a new `.env` file in the root directory of the project and copy the contents of the `.env.example` file into it.

5. Run the database migrations and seed the database. You should enter the container and run the following commands:

    ```shell
    docker-compose exec php bash

    php artisan migrate
    php artisan db:seed
    ```

6. You can run the tests by running the following command:

    ```shell
    docker-compose exec php bash

    php artisan test
    ```

7. You can see the API documentation by visiting `http://172.21.2.3/docs` in your browser.
![image](https://github.com/user-attachments/assets/0e919c3a-bec2-4e92-9421-3c8da27a53ef)
