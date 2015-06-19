# Contributing

We love pull requests from everyone. We will take a serious look at every pull request or issue.
If you want a pull request to get accepted, we ask you to follow some basic rules:

* Your code must be [PSR-2 valid](http://www.php-fig.org/psr/psr-2/)
* Your code must be unit tested
* Create new functionality on a new and separate feature branch

Once you submit a PR, Travic-CI will run the unit tests and PSR-2 code-style check. You can also run those
manually from the terminal. 

### Get your local fork

Fork `particle-php/Validator` and clone your fork to your machine:

`git clone git@github.com:`your-username`/Validator.git`

Then make sure you have [composer installed](https://getcomposer.org/download/) and run `composer install`
to download the required dependencies for testing.

### Bug fixes

The on-going development will take place in the `master` branch. Bug fixes should be based on the latest 
release branch (e.g. `v1.0`, if the bug occurs in `v1.0.0`). After this, we can merge v1.0 back into the
master branch to get the bug fix in the current development version.

If you have fixed a bug, please add unit tests that prove that the code that was broken before, now works.

# Running tests

### Unit tests

To run the tests, execute the following command:

`php vendor/bin/phpunit --coverage-clover=coverage.clover`

### PSR-2 code-style check

To check for PSR-2 code-style errors, execute the following command:

`php vendor/bin/phpcs -n --standard=PSR2 --ignore=vendor/ .`
