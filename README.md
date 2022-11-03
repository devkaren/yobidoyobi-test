# Order API

-   [Technical requirements](#technical-requirements)
-   [Installation](#installation)
-   [CI/CD](#cicd)
-   [Kubernetes](#kubernetes)
-   [HTTP](#http)
-   [Testing](#testing)

### Technical requirements

-   [nginx ^1.19](https://www.nginx.com/) + [php-fpm (^8.1)](https://www.php.net/manual/ru/install.fpm.php)
-   [php-cli ^8.1](https://www.php.net/manual/en/install.php)
-   [composer ^2](https://getcomposer.org/)
-   [postgres ^13.1](https://www.postgresql.org/download/)

For composer platform requirements run `composer check-platform-reqs` command.

## Installation

-   Run `composer install` command.
-   Setup [Application environment](#application-environment).
-   Setup [Web Configurations](#web-configurations).
-   Run `php artisan app:install` command.

**IMPORTANT!** The `php artisan app:install` command is designed only for initial project installation. It drops and rebuilds the database schema, configurations and application cache.

### Installation prerequisites

-   [Database configuration](https://laravel.com/docs/9.x/database#configuration)
-   [Queue driver prerequisites](https://laravel.com/docs/9.x/queues#driver-prerequisites)

### Application environment

Copy `.env.example` file to `.env` in the root folder, see [Laravel Environment Configuration Documentation](https://laravel.com/docs/9.x/configuration#environment-configuration) for more detailed information.

### Web Configurations

To setup **Web configurations** you will need to put following variables into [Application environment configurations](https://laravel.com/docs/9.x/configuration#environment-configuration).

| Key                            |
| ------------------------------ |
| **WEB_EMAIL_VERIFICATION_URL** |
| **WEB_RESET_PASSWORD_URL**     |

## CI/CD

### Production workflow

-   Run `composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev` command.
-   Run `php artisan app:ci -p` command.

## Kubernetes

### Prerequisites

-   [kubectl cli](https://kubernetes.io/docs/setup/production-environment/tools/kubeadm/install-kubeadm/) installed.

Following utility commands are available for use:

| Command                                      | Description                                                      |
| -------------------------------------------- | ---------------------------------------------------------------- |
| `./k8s create-namespaces`                    | Create stage namespaces                                          |
| `./k8s create-secret {namespace} {env-file}` | Create/Recreate secret for given namespace using env-file source |
| `./k8s edit-secret {namespace}`              | Edit secret for given namespace                                  |
| `./k8s apply {namespace}`                    | Apply kustomization for given namespace                          |

## HTTP

See the latest **API DOCUMENTATION** using `/docs` uri.

## Testing

-   Run `composer test` command.

### Testing prerequisites

-   [Project dependencies installed](#installation)
-   [Database connection](https://laravel.com/docs/9.x/database)

### Manual setup

-   Copy `.env.testing.example` to `.env.testing` in project root directory.
-   Setup [Database connection configuration](https://laravel.com/docs/9.x/database#configuration)
