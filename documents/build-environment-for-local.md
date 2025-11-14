# Build environment For Local
# Overview
この資料で、ローカル開発環境でアプリケーションを動作させるための環境を構築するための手順を示します。

This document provides instructions for setting up the environment to run your application in your local development environment.

# Steps
## Check list
- [ ] Clone repository
- [ ] Execute composer install
- [ ] copy ".env.example" to ".env"
- [ ] copy "docker-compose.yml.local" to "docker-compose.yml"
- [ ] Launch environment
- [ ] Confirm the environment has been successfully started
    - [ ] Check logs
    - [ ] Log in to "laravel.ncc" container and connect to the database
- [ ] Generate APP_KEY
- [ ] Execute after-pull.sh
- [ ] Confirm the Application environment has successfully started
    - [ ] Login
    - [ ] Download PDF
- [ ] Setup for testing
    - [ ] Generate APP_KEY for testing
    - [ ] migrate testing database
    - [ ] execute unit test

## Clone repository
リポジトリをクローンする。

## Execute composer install
依存関係をインストールするため、composer install コマンドを実行する。
※composer install の実行について、環境によっては下記URLを参照
https://readouble.com/laravel/9.x/ja/sail.html#installing-composer-dependencies-for-existing-projects

パーソナルアクセストークンを追加したい場合は、以下の要領で実施
（YOUR_PERSONAL_ACCESS_TOKEN の部分を実際のトークンに置き換えること）

~~~ sh
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php81-composer:latest \
bash -c "composer config github-oauth.github.com YOUR_PERSONAL_ACCESS_TOKEN && composer install --ignore-platform-reqs"
~~~

## Create ".env"
.env.example をコピーし、.env ファイルを作成する。
~~~ shell
cp .env.example .env
~~~

## Create "docker-compose.yml"
~~~shell
cp docker-compose.yml.local docker-compose.yml
~~~


## Edit ".env"
.env ファイルを編集し、適切な値を設定する。

## Launch environment
環境を立ち上げる。
~~~ shell
docker-compose up -d 
~~~

ポート番号がコンフリクトしてコンテナが立ち上がらない場合などは、1つ前の手順に戻り、各設置環境の状況にあわせてポート番号を変更する。


## Confirm the environment has been successfully started.
sail環境が正常に立ち上がったことを確認する。
Confirm that the sail environment has been successfully started.

### Check logs
ログを確認する。エラー、警告等が無いか、laravel.nccコンテナが正常に起動しているか、pgsqlコンテナの初期化プロセスが完了しているかどうか、等をチェック。
~~~ shell
docker-compose logs
~~~

正常な場合のログは以下のような内容となっているはずである。

<details>
~~~ shell
vagrant@homestead:~/adop/ncc01-test$ docker-compose logs
Attaching to ncc01-test_laravel.ncc_1, ncc01-test_mailhog_1, ncc01-test_redis_1, ncc01-test_pgsql_1
laravel.ncc_1  | 2023-01-15 18:13:11,302 INFO Set uid to user 0 succeeded
laravel.ncc_1  | 2023-01-15 18:13:11,304 INFO supervisord started with pid 1
laravel.ncc_1  | 2023-01-15 18:13:12,306 INFO spawned: 'php' with pid 16
laravel.ncc_1  | Starting Laravel development server: http://0.0.0.0:80
laravel.ncc_1  | [Sun Jan 15 18:13:12 2023] PHP 8.1.14 Development Server (http://0.0.0.0:80) started
laravel.ncc_1  | 2023-01-15 18:13:14,171 INFO success: php entered RUNNING state, process has stayed up for > than 1 seconds (startsecs)
mailhog_1      | [HTTP] Binding to address: 0.0.0.0:8025
mailhog_1      | Creating API v1 with WebPath:
mailhog_1      | Creating API v2 with WebPath:
mailhog_1      | 2023/01/15 09:13:10 Using in-memory storage
mailhog_1      | 2023/01/15 09:13:10 [SMTP] Binding to address: 0.0.0.0:1025
mailhog_1      | 2023/01/15 09:13:10 Serving under http://0.0.0.0:8025/
pgsql_1        | The files belonging to this database system will be owned by user "postgres".
pgsql_1        | This user must also own the server process.
pgsql_1        |
pgsql_1        | The database cluster will be initialized with locale "en_US.utf8".
pgsql_1        | The default database encoding has accordingly been set to "UTF8".
pgsql_1        | The default text search configuration will be set to "english".
pgsql_1        |
pgsql_1        | Data page checksums are disabled.
pgsql_1        |
pgsql_1        | fixing permissions on existing directory /var/lib/postgresql/data ... ok
pgsql_1        | creating subdirectories ... ok
pgsql_1        | selecting dynamic shared memory implementation ... posix
pgsql_1        | selecting default max_connections ... 100
pgsql_1        | selecting default shared_buffers ... 128MB
pgsql_1        | selecting default time zone ... Etc/UTC
pgsql_1        | creating configuration files ... ok
pgsql_1        | running bootstrap script ... ok
pgsql_1        | performing post-bootstrap initialization ... ok
pgsql_1        | syncing data to disk ... ok
pgsql_1        |
pgsql_1        |
pgsql_1        | Success. You can now start the database server using:
pgsql_1        |
pgsql_1        |     pg_ctl -D /var/lib/postgresql/data -l logfile start
pgsql_1        |
pgsql_1        | initdb: warning: enabling "trust" authentication for local connections
pgsql_1        | You can change this by editing pg_hba.conf or using the option -A, or
pgsql_1        | --auth-local and --auth-host, the next time you run initdb.
pgsql_1        | waiting for server to start....2023-01-15 09:13:12.260 UTC [48] LOG:  starting PostgreSQL 14.5 (Debian 14.5-2.pgdg110+2) on x86_64-pc-linux-gnu, compiled by gcc (Debian 10.2.1-6) 10.2.1 20210110, 64-bit
pgsql_1        | 2023-01-15 09:13:12.262 UTC [48] LOG:  listening on Unix socket "/var/run/postgresql/.s.PGSQL.5432"
pgsql_1        | 2023-01-15 09:13:12.269 UTC [49] LOG:  database system was shut down at 2023-01-15 09:13:11 UTC
pgsql_1        | 2023-01-15 09:13:12.272 UTC [48] LOG:  database system is ready to accept connections
pgsql_1        |  done
pgsql_1        | server started
pgsql_1        | CREATE DATABASE
pgsql_1        |
pgsql_1        |
pgsql_1        | /usr/local/bin/docker-entrypoint.sh: running /docker-entrypoint-initdb.d/10-create-testing-database.sql
pgsql_1        | CREATE DATABASE
pgsql_1        |
pgsql_1        |
pgsql_1        | waiting for server to shut down....2023-01-15 09:13:13.004 UTC [48] LOG:  received fast shutdown request
pgsql_1        | 2023-01-15 09:13:13.006 UTC [48] LOG:  aborting any active transactions
pgsql_1        | 2023-01-15 09:13:13.007 UTC [48] LOG:  background worker "logical replication launcher" (PID 55) exited with exit code 1
pgsql_1        | 2023-01-15 09:13:13.007 UTC [50] LOG:  shutting down
pgsql_1        | 2023-01-15 09:13:13.022 UTC [48] LOG:  database system is shut down
pgsql_1        |  done
pgsql_1        | server stopped
pgsql_1        |
pgsql_1        | PostgreSQL init process complete; ready for start up.
pgsql_1        |
pgsql_1        | 2023-01-15 09:13:13.121 UTC [1] LOG:  starting PostgreSQL 14.5 (Debian 14.5-2.pgdg110+2) on x86_64-pc-linux-gnu, compiled by gcc (Debian 10.2.1-6) 10.2.1 20210110, 64-bit
pgsql_1        | 2023-01-15 09:13:13.121 UTC [1] LOG:  listening on IPv4 address "0.0.0.0", port 5432
pgsql_1        | 2023-01-15 09:13:13.121 UTC [1] LOG:  listening on IPv6 address "::", port 5432
pgsql_1        | 2023-01-15 09:13:13.126 UTC [1] LOG:  listening on Unix socket "/var/run/postgresql/.s.PGSQL.5432"
pgsql_1        | 2023-01-15 09:13:13.131 UTC [64] LOG:  database system was shut down at 2023-01-15 09:13:13 UTC
pgsql_1        | 2023-01-15 09:13:13.135 UTC [1] LOG:  database system is ready to accept connections
redis_1        | 1:C 15 Jan 2023 09:13:10.743 # oO0OoO0OoO0Oo Redis is starting oO0OoO0OoO0Oo
redis_1        | 1:C 15 Jan 2023 09:13:10.743 # Redis version=7.0.7, bits=64, commit=00000000, modified=0, pid=1, just started
redis_1        | 1:C 15 Jan 2023 09:13:10.743 # Warning: no config file specified, using the default config. In order to specify a config file use redis-server /path/to/redis.conf
redis_1        | 1:M 15 Jan 2023 09:13:10.744 * monotonic clock: POSIX clock_gettime
redis_1        | 1:M 15 Jan 2023 09:13:10.748 * Running mode=standalone, port=6379.
redis_1        | 1:M 15 Jan 2023 09:13:10.748 # Server initialized
redis_1        | 1:M 15 Jan 2023 09:13:10.748 # WARNING Memory overcommit must be enabled! Without it, a background save or replication may fail under low memory condition. Being disabled, it can can also cause failures without low memory condition, see https://github.com/jemalloc/jemalloc/issues/1328. To fix this issue add 'vm.overcommit_memory = 1' to /etc/sysctl.conf and then reboot or run the command 'sysctl vm.overcommit_memory=1' for this to take effect.
redis_1        | 1:M 15 Jan 2023 09:13:10.748 * Ready to accept connections
~~~
</details>

### Log in to "laravel.ncc" container and connect to the database

- laravel.ncc コンテナへログイン

~~~shell
docker-compose exec laravel.ncc bash
~~~

- ログインしたlaravel.ncc コンテナで、pgsqlコンテナのデータベースに接続できることを確認

~~~shell
php artisan tinker
DB::select("select now()");
~~~

確認時の実行例(タイムスタンプが返ってくればOK)

<details>

~~~ shell
vagrant@homestead:~/adop/ncc01$ docker-compose exec laravel.ncc bash
root@ed597664e15e:/var/www/html# php artisan tinker
Psy Shell v0.11.1 (PHP 8.1.14 ― cli) by Justin Hileman
>>> DB::select("select now()");
=> [
     {#3951
       +"now": "2023-01-15 09:03:10.807536+00",
     },
   ]
>>>
~~~

</details>

## Generate APP_KEY
php artisan key:generateコマンドを実行し、APP_KEYを生成する
~~~ sh
docker-compose exec laravel.ncc bash
php artisan key:generate
~~~

## Execute after-pull.sh
operation/after-pull.sh を実行する。
~~~shell
docker-compose exec laravel.ncc bash
operation/after-pull.sh
~~~

##  Confirm the Application environment has successfully started
ブラウザで環境にアクセスし、以下の内容について確認する。

### Login
- 以下のテストアカウントによるログインが成功すること(パスワードは別途確認)
    - test01@balocco.info (権限3:エンドユーザー)
    - test02@balocco.info (権限2:事務局ユーザー)

### Download PDF
test01@balocco.info アカウントでログイン後、PDFダウンロード機能を利用してPDFファイルをダウンロードできることを確認する。

## Setup for testing
ユニットテスト実行のために必要なデータベースのセットアップを行う。

### Generate APP_KEY for testing
テスト実行用の設定ファイルにAPP_KEYを設定する。

~~~ shell
php artisan key:generate --env=testing
~~~

### migrate testing database
テスト用のデータベースに対してマイグレーションを実行する。

~~~ shell
docker-compose exec laravel.ncc bash
php artisan migrate --env=testing
~~~

※設定は、.env.testing が参照される

### execute unit test
~~~shell
docker-compose exec laravel.ncc bash
composer phpunit
~~~
