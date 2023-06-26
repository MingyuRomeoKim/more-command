
# more-command
add artisan command What I Want!
<br />

## Installation
Require the package with composer using the following command:

```
composer require mingyukim/moreCommand
```

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require": {
      "mingyukim/moreCommand": "*"
}
```

## Publish Package Configuration
```shell
 php artisan vendor:publish --provider="MingyuKim\MoreCommand\MoreCommandProvider" --tag="config"
```

### To Change Default Namespace [config/moreCommand.php]
```php
<?php
return [
    'repository-namespace' => 'App', // Your Desire Namespace for Repository Classes   
];
```

## Make Repository

__Create a repository Class.__\
`php artisan make:repositories {--queue} {--print}`

Example:
```
php artisan make:repositories
```

# License
The MIT License (MIT). Please see [License](LICENSE) for more information.
