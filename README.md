# TinyCMS バックエンド

Webサイトのお知らせ・ブログを管理するREST APIサーバーです。ユーザー認証、記事管理、画像アップロード機能を提供します。

## セットアップ

```bash
# データベース初期化
php db/db.php

# サーバー起動
php -S localhost:3000
```

`http://localhost:3000/api/` でAPIが利用可能になります。

## API エンドポイント

- `POST /api/login.php` - ログイン
- `POST /api/register.php` - 新規登録
- `GET /api/get_articles.php` - 記事一覧取得
- `POST /api/post_article.php` - 記事作成
- `POST /api/edit_article.php` - 記事編集
- `POST /api/delete_article.php` - 記事削除
- `GET /api/me.php` - 現在のユーザー確認

## 環境設定

CORS設定で許可するドメインを変更してください（`api/` 内の各ファイル）：

```php
header("Access-Control-Allow-Origin: http://localhost:5173");
```
