# アプリケーション名
coachtech-fleamarket

## 環境構築
- Dockerのビルドからマイグレーション、シーディングまでを記述する
  - `$ docker-compose up -d --build`
  - `$ docker-compose exec php bash`
  - `$ composer install`
  - `.env`ファイルの設定
  - `$ php artisan migrate --seed`

## 使用技術（実行環境）
- Laravel 8.75
- PHP 7.4.9
- MySQL 8.0.26
- Docker / Docker Compose
- GitHub
- phpMyAdmin（http://localhost:8080）

## ER図
＜－－－ 作成したER図の画像をここに貼る　－－－＞

## URL
- 開発環境：http://localhost:8000
