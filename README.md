
# more-command
add artisan command What I Want!
<br />

## Installation
Require the package with composer using the following command:

```
composer require mingyukim/more-command
```

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require": {
      "mingyukim/more-command": "*"
}
```

## Publish Package Configuration
```shell
 php artisan vendor:publish --provider="MingyuKim\MoreCommand\MoreCommandProvider" --tag="config"
```

### To Change Default Namespace [config/more-command.php]
```php
<?php
return [
    'repository-namespace' => 'App', // Your Desire Namespace for Repository Classes   
];
```

## Make All Repositories

__Create a repository Class.__\
`php artisan make:repositories {--print}`

Example:
```
php artisan make:repositories
```

## Make Each Repository

__Create a repository Class.__\
`php artisan make:repository {repositoryName} {--print}`

Example:
```
php artisan make:repository TestRepository
```

# License
The MIT License (MIT). Please see [License](LICENSE) for more information.
