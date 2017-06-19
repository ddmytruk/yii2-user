# Установка Yii2-user

### 1. composer.json

```bash
"ddmytruk/yii2-user": "@dev"
```

### 2. Подключение модуля

> **NOTE:** Не должно быть другово модуля "user".

Добавить модуль в config/web.php:

```php
'modules' => [
    'user' => [
        'class' => 'ddmytruk\user\Module',
    ],
],
```

### 3. База даных

```bash
$ php yii migrate/up --migrationPath=@vendor/ddmytruk/yii2-user/migrations
```