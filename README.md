
# Oc8 - project for openclassroom

## Mission

Software overhaul of an old Symfony 3 project running under Php-5 :(

Raise the site from the ashes and bring it up to date with Symfony 7 ![Static Badge](https://img.shields.io/badge/symfony-7-0) and Php-8.3.4 ![Static Badge](https://img.shields.io/badge/php-8.3.4-%23777BB4).

Produce technical documentation and a comparative quality audit of the work carried out.

Make patches and add expected functionalities :

- attach a task to a user when saving it
- the author cannot be changed
- tasks already created are anonymous
- choice of role when creating or modifying: user/admin
- only admins can access the user management page
- tasks can only be deleted by their author

Create unitary tests under PhpUnit ![Static Badge](https://img.shields.io/badge/phpUnit-11.0-%23828FFF).

## Requirements

Based on the last Php-8.3.4 <img src="https://img.shields.io/badge/php-8.3.4-%23777BB4?logo=php" alt="php banner">, the architecture of the software respect of the segregation of the functions, recommended by the <a href="https://fr.wikipedia.org/wiki/SOLID_(informatique)">SOLID principle</a>.

## Installation

Supposing you have <a href="https://git-scm.com/">GIT</a> installed, clone this repository :

    git clone https://github.com/agence2dav/oc8

Then, let <a href="https://getcomposer.org/">Composer</a> install the components from the `composer.json`:

Security Audit

    composer audit

Now it's time to :

    composer install

Create your own `.env.local`, that will replace the default datas from .env.
Especially the database:

    DATABASE_URL="mysql://{dbname-root}:{password}@127.0.0.1:3306/oc8"

And do it again in `.env.test` for the unitary tests, addin "_test" to the database name:

    DATABASE_URL="mysql://{dbname-root}:{password}@127.0.0.1:3306/oc8_test"

Once the database is set, it's time to install it:

    php bin/console doctrine:database:create

Migrate the schema of the databases for Symfony:

    php bin/console make:migration

Now persist this schema on the database (that create the tables) :

    php bin/console doctrine:migration:migrate

Your site is now ready. To perform tests we can write a set of false datas:

    php bin/console doctrine:fixtures:load

And do it again to create a set for the unitary tests :

    php bin/console --env=test doctrine:fixtures:load

If you need to redo all that, you can kill the database:

    php bin/console doctrine:database:drop --force

In localhosting only, you can using the server of Symfony:

    symfony server:start -d

From there, your site will totally be operationnal.

## Documentation

The test-coverage can be found at `/public/test-coverage`

The quality audit can be found at: `/documentation/Audit de qualité.pdf`

The technical documentation ca be found at `/documentation/Documentation technique.pdf`
