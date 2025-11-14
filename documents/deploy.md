# この資料について

## 概要

デプロイ手順についての情報を提供する

## 対象

開発担当者

# 初期環境構築時の手順(初回のみ)

ミドルウェア等のインストールが完了し、github からソースコードを clone してきた直後の手順について以下に説明します。

※以下、homestead12.6 環境の場合のコマンド例を記載します。


## データベースの作成

postgresqlサーバーが起動していない場合は起動する。

※ vagrant ユーザのデフォルトパスワードは vagrant 

~~~
# postgresql 起動
/etc/init.d/postgresql start
~~~

本アプリケーション用のデータベースと、テストに利用するためのデータベース、計2つを作成する。

※データベース名は任意でOK。作成したデータベース名、接続情報等は、後述の設定ファイルに記載します。

~~~
# データベース作成
sudo -u postgres createdb -E utf8 -T template0 ncc01
sudo -u postgres createdb -E utf8 -T template0 ncc01_testing
~~~

データベースが作成できたことを確認するため、接続を試行する。

~~~
# 接続確認
sudo -u postgres psql ncc01
sudo -u postgres psql ncc01_testing
~~~

接続が成功することを確認したら、次の手順に進む。

## 設定ファイル

###  .env の作成
.env ファイルを作成する。
~~~ 
cp .env.example .env
php artisan key:generate
~~~

###  .env の設定変更
下記を参考に、各設置環境にあわせた内容に修正する。

※主に、Databseに関する設定、Mailに関する設定を修正すればOK。

~~~
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:+PQu77o/7CEzB24txBgcPI+WtJmVdQbYJASXNgzoZJA=
APP_DEBUG=true
APP_URL=https://homestead.ncc01

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ncc01
DB_USERNAME=homestead
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@balocco.info
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
~~~

### .env.testing の作成

テスト用の設定ファイル .env.testing を作成する。

~~~
cp .env .env.testing
php artisan key:generate --env=testing
~~~

###   .env.testing の設定変更
テスト用のデータベースに接続するよう、設定の修正を行う。

~~~
DB_DATABASE=ncc01_testing
~~~



# デプロイ手順

アプリケーションのデプロイを行う手順について説明する。

## コマンド
### ローカル開発環境

ローカル開発環境においては、以下のコマンドを順次実行する。

（ソースコード更新内容によって、必要の内コマンドは割愛してよい。判断がつかない場合は全て実行。）

~~~
git pull
composer install 
npm ci
php artisan migrate
npm run dev
composer dump-autoload
~~~

### 開発サーバー
http://3.113.26.217/  
- Basic認証あり
- キャッシュ等を生成するコマンドを追加で実行
- Laravel sail のコンテナにログインする必要がある点に注意する。

~~~
git pull 
docker exec -it ncc01_laravel.ncc_1 bash
operation/after-pull.sh
~~~

### 本番サーバー
https://ncr-datause.ncc.go.jp/
- キャッシュ等を生成するコマンドを追加で実行
- Laravel sail のコンテナにログインする必要がある点に注意する。
- npm run prod 
 
~~~
git pull 
docker exec -it ncc01_laravel.ncc_1 bash
operation/after-pull.sh
~~~


# 注意事項/メモ

- composer.lock の内容でインストールを行う。

- 開発時に新しいパッケージを導入する場合に、composer.lockの変更差分が最小となるよう注意すること。開発ブランチで composer update を実行するのは禁止です。理由は下記を参照してください。

  [https://qiita.com/tanakahisateru/items/ff4118ffd6a404bceb64](https://qiita.com/tanakahisateru/items/ff4118ffd6a404bceb64)

※composer update を実行するのは、依存パッケージの更新を意図して実施する場合だけにしてね



- package-lock.json の内容を参照してインストールするので、npm ciを利用。npm install ではない。

- 本番環境の場合、npm run prod 、開発環境の場合、npm run dev

