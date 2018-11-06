# apikey-auth

API key Authentication for Laravel and Lumen.

## 安装

```bash
composer require leonis/apikey-auth
```

## 配置

### Laravel

1. 在 `config/app.php` 注册 ServiceProvider (Laravel 5.5 及以上无需手动注册)

    ```php
           'providers' => [
               // ...
               Leonis\ApiKeyAuth\ApiKeyAuthServiceProvider::class,
           ],
           ```

2. 创建配置文件：

    ```bash
    php artisan vendor:publish --provider="Leonis\ApiKeyAuth\ApiKeyAuthServiceProvider"
    ```

3. 运行数据库迁移

    ```bash
    php artisan migrate
    ```

### lumen

1. 在 `bootstrap/app.php` 注册 ServiceProvider

    ```php
    $app->register(Leonis\ApiKeyAuth\ApiKeyAuthServiceProvider::class);
    ```

2. 复制配置和数据库迁移文件  

    将 `vendor/leonis/apikey-auth/config/config.php` 拷贝到 `config` 目录下，并将文件名改成 `api_key.php`;
    将 `vendor/leonis/apikey-auth/database/migrations` 目录中的文件拷贝到 `database/migrations` 目录下。

3. 运行数据库迁移

    ```bash
    php artisan migrate
    ```

## 使用

1. 修改 `auth.php` 配置文件中的 `guards` :
    ```php
    'guards' => [
            // ...
    
            'api' => [
                'driver' => 'api_key',
                'provider' => 'users',
            ],
        ],
    ```

2. 为用户生成 Api Key :
    ```php
    (new ApiKey)->fromUser(User::first());
    (new ApiKey)->fromId($user->id);
    ```

3. 请求 API :

    TODO
    
## License

[MIT](https://opensource.org/licenses/MIT)