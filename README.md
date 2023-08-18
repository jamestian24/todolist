## todo list
## 環境
- PHP 8.1.17
- Laravel 10.19.0

### 設定.env
1. 生成.env檔案

    ```bash
    cp .env.example .env
    ```
2. 產生APP_KEY

    ```bash
    php artisan key:generate
    ```

#### 資料庫建置
```
    php artisan migrate:fresh
    php artisan db:seed
```

#### 運行
```
    php artisan serve
```

### 產生API文件
```
php artisan scribe:generate
```
