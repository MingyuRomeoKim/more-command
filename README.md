
# more-command
artisan make에 없지만, 자주 사용하는 패턴 및 클래스를 command 명령어로 쉽게 생성하게 만들어주는 라이브러리 
<br />

## 설치 방법
다음의 명령어를 사용해 composer를 통해 패키지를 설치합니다:


```
composer require mingyukim/more-command
```

또는 composer.json의 require-dev 섹션에 다음을 추가하고 `composer update`를 실행합니다:


```json
"require": {
      "mingyukim/more-command": "*"
}
```

## 패키지 설정 파일 발행

```shell
 php artisan vendor:publish --provider="MingyuKim\MoreCommand\MoreCommandProvider" --tag="config"
```

### 기본 네임스페이스 변경하기 [config/more-command.php]
```php
<?php
return [
    'repository-namespace' => 'App', // 저장소 클래스에 대한 원하는 네임스페이스
    'trait-namespace' => 'App', // Traits에 대한 원하는 네임스페이스
    'service-namespace' => 'App', // 서비스에 대한 원하는 네임스페이스   
];
```

## 모든 Repository 생성

__App/Models 폴더를 참조하여 repository Class 를 생성합니다.__\
`php artisan make:repositories {--print}`

예제:
```
php artisan make:repositories
```

## 각각의 Repository 생성

__하나의 repository Class 를 생성합니다.__\
`php artisan make:repository {repositoryName} {--print}`

예제:
```
php artisan make:repository TestRepository
```
결과물:
```
/app/Repositories/TestRepository.php
```

## 각각의 Trait 생성

__하나의 Trait File 을 생성합니다.__\
`php artisan make:trait {triatName} {--print}`

예제:
```
php artisan make:trait TestTrait
```
결과물:
```
/app/Traits/TestTrait.php
```

## 각각의 Service 생성

__하나의 Service Class 를 생성합니다.__\
`php artisan make:service {serviceName} {--print}`

예제:
```
php artisan make:service TestService
```
결과물:
```
/app/Services/TestService.php
```

## 각각의 View Blade 생성

__Create a View.__\
`php artisan make:view {viewName} {--print}`

예제:
```
php artisan make:view test
```
결과물:
```
/resources/views/test.blade.php
```

# 라이선스
MIT 라이선스(MIT). 자세한 정보는 라이선스를 참고하세요.