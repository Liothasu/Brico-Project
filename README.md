# :hammer_and_wrench: Final year project - Management of a hardware store :hammer_and_wrench:

 This repository contains a Symfony project integrated with Webpack for managing front-end resources.

[![made-for-VSCode](https://img.shields.io/badge/Made%20for-VSCode-1f425f.svg)](https://code.visualstudio.com/) [![Npm package version](https://badgen.net/npm/v/express)](https://npmjs.com/package/express) [![PHP Version Require](http://poser.pugx.org/phpunit/phpunit/require/php)](https://packagist.org/packages/phpunit/phpunit)
[![Generic badge](https://img.shields.io/badge/Finish-no-red.svg)](https://shields.io/)



## :wrench: Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/Liothasu/Brico-Project.git
    ```

2. **Install PHP dependencies:**

    Make sure you have PHP installed on your machine. Then, run:

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies:**

    Make sure you have Node.js and npm installed on your machine. Then, run:

    ```bash
    npm install
    ```

4. **Configure environment variables:**

    Duplicate the `.env.example` file and rename it to `.env`. Modify this file to configure your database and other settings as needed.

5. **Create the database:**

    Run the Symfony command to create the database:

    ```bash
    php bin/console doctrine:database:create
    ```

6. **Run migrations:**

    Apply migrations to create the database schema:

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

7. **Compile assets:**

    Compile front-end resources using Webpack:

    ```bash
    npm run build
    ```

8. **Start the development server:**

    Launch the Symfony development server:

    ```bash
    symfony server:start
    ```

## :woman_technologist: Usage

After following the installation steps, you can access the application in your browser at [https://127.0.0.1:8000](https://127.0.0.1:8000)
