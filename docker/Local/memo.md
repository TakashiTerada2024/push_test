# ローカル環境についてのメモ（山本）

## 経緯、メモ
- ベトナムチーム参画時(2023-01-15)に整備を開始。
- 当初、本番、ステージング用の設定を流用しようとしたが、2023-01-15時点でのprod、stg用の設定は、ubuntuバージョンが古く、サポート期限が終了しているためビルドが通らない状態となっていた。
- ビルドの問題を回避する目的で、laravel/sailのバージョンを1.13.2から1.14.11まで上げた状態で、環境構築の整備をやり直し。
- laravel/sail 1.14.11 をインストールした上で、php artisan sail:install 実行し、docker-compose.yml を作成。
- サービス名を、laravel.test からlaravel.ncc に変更
- 作成したdocker-compose.ymlを docker-compose.yml.local としてリポジトリにコミット。
- また、あわせて vendor/laravel/sail/runtimes/8.1 ディレクトリから、utils/Local に資材をコピーした上で、ビルドコンテキストをutils/Local に変更。 
- stg環境のDockerfileを参考にして、PDFダウンロード機能に必要な libxrender1 のインストールを追加。

## 残タスク
- stg、prodに習って、ビルド時にローカル用のnginx設定ファイル（ncc.conf）をコピーするように改善したい
- ローカル環境でもSSL接続できるよう、https-portal を導入したい
