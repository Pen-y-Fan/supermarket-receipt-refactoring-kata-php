# Theatrical Players Refactoring Kata - PHP version

See the [top level readme](../../Theatrical-Players-Refactoring-Kata/README.md) for general information
 about this exercise. Download the PDF of the first chapter of
  ['Refactoring' by Martin Fowler, 2nd Edition](https://www.thoughtworks.com/books/refactoring2) which contains a worked
   example of this exercise, in javascript.

## Installation

The project uses:

- [PHP 7.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org)

Recommended:

- [Git](https://git-scm.com/downloads)

Clone the repository

```sh
git clone git@github.com:emilybache/Theatrical-Players-Refactoring-Kata.git
```

or

```shell script
git clone https://github.com/emilybache/Theatrical-Players-Refactoring-Kata.git
```

Install all the dependencies using composer:

```sh
cd ./Theatrical-Players-Refactoring-Kata/php
composer install
```

## Dependencies

The project uses composer to install:

- [PHPUnit](https://phpunit.de/)
- [ApprovalTests.PHP](https://github.com/approvals/ApprovalTests.php)
- [PHPStan](https://github.com/phpstan/phpstan)
- [Easy Coding Standard (ECS)](https://github.com/symplify/easy-coding-standard) 
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/wiki)

## Folders

- `src` - Contains the **StatementPrinter** Class along with the setup classes. Only **StatementPrinter.php** is
    refactored. 
- `tests` - Contains the corresponding tests. There should be no need to amend the test.
  - `approvals` - Contains the text output for the tests. There should be no need to amend.

## Testing

PHPUnit is used to run tests, to help this can be run using a composer script. To run the unit tests, from the root of
 the project run:

```shell script
composer test
```

On Windows a batch file has been created, similar to an alias on Linux/Mac (e.g. `alias pu="composer test"`), the same
 PHPUnit `composer test` can be run:

```shell script
pu
```

### Tests with Coverage Report

To run all test and generate a html coverage report run:

```shell script
composer test-coverage
```

The coverage report is created in /builds, it is best viewed by opening **index.html** in your browser.

The [XDEbug](https://xdebug.org/download) extension is required for coverage report generating. 

## Code Standard

Easy Coding Standard (ECS) is used to check for style and code standards,
 **[PSR-12](https://www.php-fig.org/psr/psr-12/)** is used. As the code is constantly being refactored only run code
  standard checks once the chapter is complete.

### Check Code

To check code, but not fix errors:

```shell script
composer check-cs
``` 

On Windows a batch file has been created, similar to an alias on Linux/Mac (e.g. `alias cc="composer check-cs"`), the
 same ECS `composer check-cs` can be run:

```shell script
cc
```

### Fix Code

Many code fixes are automatically provided by ECS, if advised to run --fix, the following script can be run:

```shell script
composer fix-cs
```

On Windows a batch file has been created, similar to an alias on Linux/Mac (e.g. `alias fc="composer fix-cs"`), the same
 ECS `composer fix-cs` can be run:

```shell script
fc
```

## Static Analysis

PHPStan is used to run static analysis checks. As the code is constantly being refactored only run static analysis
  checks once the chapter is complete.

```shell script
composer phpstan
```

On Windows a batch file has been created, similar to an alias on Linux/Mac (e.g. `alias ps="composer phpstan"`), the
 same PHPStan `composer phpstan` can be run:

```shell script
ps
```

**Happy coding**!
