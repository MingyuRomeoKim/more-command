
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
`php artisan make:repository {--print} {repositoryName}`

Example:
```
php artisan make:repository TestRepository
```
Output:
```
/app/Repositories/TestRepository.php
```

## Make Each Trait

__Create a Trait.__\
`php artisan make:trait {--print} {triatName}`

Example:
```
php artisan make:trait TestTrait
```
Output:
```
/app/Traits/TestTrait.php
```

## Make Each Service

__Create a Service.__\
`php artisan make:service {--print} {serviceName}`

Example:
```
php artisan make:service TestService
```
Output:
```
/app/Services/TestService.php
```

## Make Each View

__Create a View.__\
`php artisan make:view {--print} {viewName}`

Example:
```
php artisan make:view test
```
Output:
```
/resources/views/test.blade.php
```
# License
The MIT License (MIT). Please see [License](LICENSE) for more information.
