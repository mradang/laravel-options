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
