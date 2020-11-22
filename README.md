## 安装

```shell
composer require mradang/laravel-options -vvv
```

## 配置

1. 发布配置文件

```shell
php artisan vendor:publish --provider="mradang\\LaravelOptions\\LaravelOptionsServiceProvider"
```

2. 刷新数据库迁移

```bash
php artisan migrate:refresh
```

## 添加的内容

### 添加的数据表迁移

- options

## 使用方法

### 直接存取

```php
// set
\Option::set('foo', 'bar');
\Option::set(['foo' => 'bar', 'bar' => 'baz']);

// get
\Option::get('foo'); // bar
\Option::get(['foo', 'bar']); // ['foo' => 'bar', 'bar' => 'baz']
\Option::get('baz', 'abc'); // abc

// has
\Option::has('baz'); // false

// remove
\Option::remove('foo');
\Option::remove(['foo', 'bar']);
```

### 配置路由

修改配置文件 options.php 后配置路由
laravel-options 未自动配置路由，方便使用者自定义路由及权限控制

```php
// get example
Route::post(
    'getExampleOptions',
    [\mradang\LaravelOptions\Controllers\OptionsController::class, 'getExampleOptions'],
);

// set example
Route::post(
    'setExampleOptions',
    [\mradang\LaravelOptions\Controllers\OptionsController::class, 'setExampleOptions'],
);
```
