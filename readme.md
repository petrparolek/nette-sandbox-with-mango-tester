Nette Sandbox with Mago Tester
=============

This is a simple pre-packaged and pre-configured application using the [Nette](https://nette.org)
that you can use as the starting point for your new applications.

[Nette](https://nette.org) is a popular tool for PHP web development.
It is designed to be the most usable and friendliest as possible. It focuses
on security and performance and is definitely one of the safest PHP frameworks.

If you like Nette, **[please make a donation now](https://nette.org/donate)**. Thank you!

## Install using [docker](https://github.com/docker/docker/)

1) At first, use GIT to download this project.

2) Run command:

   ```
   docker-compose up
   ```

3) Open http://test.localhost

If you need enter into container (such as `composer update`) run command `docker-compose run web bash`.

If you need run tests enter into container:
1) run command `docker-compose run --rm console bash`
2) run command `make qa tests`
