
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
    'trait-namespace' => 'App', // Your Desire Namespace for Traits   
    'service-namespace' => 'App', // Your Desire Namespace for Services   
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

## Make Each Trait

__Create a Trait.__\
`php artisan make:trait {triatName} {--print}`

Example:
```
php artisan make:trait TestTrait
```

## Make Each Service

__Create a Service.__\
`php artisan make:service {serviceName} {--print}`

Example:
```
php artisan make:service TestService
```

# License
The MIT License (MIT). Please see [License](LICENSE) for more information.
