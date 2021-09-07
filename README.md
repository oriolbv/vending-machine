# Vending Machine

Vending Machine system implemented in PHP.

To run docker:

```docker build -t oburgaya/vending-machine . ```

```docker run -it --rm --name test oburgaya/vending-machine /bin/bash```

When docker is up, use the following command to run the application:

```php bin/application run```

And to run the tests:

```php vendor/bin/phpunit```