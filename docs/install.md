# Установка Yii2-user

### 1. Download

```bash
"ddmytruk/yii2-user": "@dev"
```

### 2. Configure

> **NOTE:** Не должно быть другово модуля "user".

Добавить модуль в config/web.php:

```php
'modules' => [
    'user' => [
        'class' => 'ddmytruk\user\Module',
    ],
],
```

### 3. Database schema

```bash
$ php yii migrate/up --migrationPath=@vendor/ddmytruk/yii2-user/migrations
```