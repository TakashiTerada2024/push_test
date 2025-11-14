# コード品質について

## 前提

この文書における次の各キーワード「しなければならない（ MUST ）」、 「してはならない（ MUST NOT ）」、「要求されている（ REQUIRED ）」、 「することになる（ SHALL ）」、「することはない（ SHALL NOT ）」、 「する必要がある（ SHOULD ）」、「しないほうがよい（ SHOULD NOT ）」、 「推奨される（ RECOMMENDED ）」、「してもよい（ MAY ）」、 「選択できる（ OPTIONAL ）」は、[RFC2119](https://www.ipa.go.jp/security/rfc/RFC2119JA.html)  で述べられているように 解釈されるべきものです。

## 概要

コード品質の基準、自動検査で生じた警告に対応する際の統一的な方針、自動検査内容、設定ファイルの位置等に関する情報を提供する。

## 対象

全担当者



# 静的解析

[composer.json](../composer.json) 内のscriptsに、静的解析のためのコマンドエイリアスを定義してあります。



## qa-static

静的解析のチェックスクリプトすべてを順番に実行します。

```sh
composer qa-static
```

qa-staticは、内部的に個々のチェックスクリプトを呼び出します。実際に実行されるコマンド内容は、composer.json内を参照してください。以下に、各チェックスクリプトに関する主要な情報と、警告が発生した場合の対応について示します。

| チェックスクリプト       | 設定ファイル                          | レベル | 警告に対する対応 | 備考/抑止コメントフォーマット等                              |
| ------------------------ | ------------------------------------- | ------ | ---------------- | ------------------------------------------------------------ |
| composer-require-checker | -                                     | -      | **REQUIRED**     |                                                              |
| parallel-lint            | -                                     | -      | **REQUIRED**     |                                                              |
| coding-standards         | [phpcs_psr12.xml](../phpcs_psr12.xml) | -      | **REQUIRED**     | phpcsを利用。<br />修正にはphpcbf を利用する。               |
| psalm                    | [psalm.xml](../psalm.xml)             | info   | OPTIONAL         |                                                              |
|                          |                                       | error  | **RECOMMENDED**  | @psalm-suppress                                              |
| cognitive-complexity     | [phpcs_cc.xml](../phpcs_cc.xml)       | -      | **RECOMMENDED**  | phpcs利用<br />phpcs:disable CognitiveComplexity<br />phpcs:enable |
| phpmd                    | [phpmd.xml](../phpmd.xml)             | -      | **RECOMMENDED**  | @SuppressWarnings(PHPMD.ルール名)                            |
| phpcpd                   | -                                     | -      | **RECOMMENDED**  | @SuppressWarnings(*PHPCPD*)                                  |



「警告に対する対応」の具体的内容について、本文書内においては下記の通り定めます。

- REQUIRED : 理由を問わず必ず修正しなければならない。

- RECOMMENDED：原則として修正が必要。マージ担当者の承認が得られた場合は、警告を抑止するアノテーションを事由とともにコメントとして追記し、警告の原因を解消せずにコミットしても良い。マージ担当者の承認が得られない場合は、警告の原因となるコードを修正する。

- OPTIONAL：リソースに余裕があれば修正する。修正しなくても良い。



上記内容に従って、警告に対する修正を適切に実施し、コード品質の保持に努めてください。  



以下に、各スクリプトの詳細について簡単に説明します。



## composer-require-checker

composer.json 内 のrequire-dev ディレクィブに指定されているパッケージに依存している製品コードの有無を検査する。  

この検査がNGとなる場合、開発環境でのみインストールされるコードに製品コードが依存している状態である。  

本番環境にデプロイすると障害が発生する状態であるため、警告が発生しない状態にコードを修正しなければならない。

## parallel-lint

syntaxチェック。

この検査がNGとなる場合、すべての環境において障害が発生するため、警告が発生しない状態となるようコードを修正しなければならない。
## coding-standards

PSR-12に準拠していることの検証。

この検査がNGとなる場合コード規約に違反しているため、警告が発生しないようコードを修正しなければならない。

※phpcbf 実行により、規約違反箇所の一部は自動的に修正することができる。


## psalm

主に以下のような問題点を検出する。  

- 廃止予定(deprecated)の関数、メソッド、クラスの利用。
- 配列キーの重複。
- 型の不整合。戻り値の型宣言と異なる型のreturnなど。
- アクセスできないメンバ変数やメソッドへのアクセス。privateや@internalなど。

この検査でエラーとなる場合であっても直ちに障害が発生する訳ではない。ただし、潜在的なバグにつながる問題点を解消することは重要であるため、原則として警告が発生しないようソースコードを修正することが推奨される。

## cognitive-complexity

認知的複雑度（コードの読みづらさを定量的に評価した指標）の検査。

保守性の観点から、この検査に合格するようコードを修正することが推奨される。

[https://qiita.com/suzuki_sh/items/824c36b8d53dd2f1efcb](https://qiita.com/suzuki_sh/items/824c36b8d53dd2f1efcb)

警告抑止に関してはPHPCSのドキュメントを参照。

https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#ignoring-files-and-folders

## phpmd

「望ましくないコード」の検出。潜在的なバグ原因となるコードや、バグを発生させる確率の高いコード状態を定量的に検査する。保守性の観点、また潜在的バグ原因となるコードを減らす目的で、この検査に合格するようコードを修正することが推奨される。  

phpmdにおける検査ルールの定義については、以下のドキュメントを参照。

[https://phpmd.org/rules/codesize.html](https://phpmd.org/rules/codesize.html)

部分的な翻訳 [https://qiita.com/rana_kualu/items/097db09f711fe15eddb7#npathcomplexity](https://qiita.com/rana_kualu/items/097db09f711fe15eddb7#npathcomplexity)

### 例外として許可する警告について
####  StaticAccess
- Controllerクラスでは、Viewファサードを介したStaticAccessを許可。
- Controllerクラスでは、Redirectファサードを介したStaticAccessは許可。
- Repository クラスにおいては、Eloquentモデルに対するStaticAccessは許可。
 

## phpcpd

コピー&ペーストの検出。

保守性の観点から原則として修正することが推奨される。

※単純に該当コードを共通化するとかえって保守性が悪化することも多い。SOLID原則が優先。



# 動的テスト

## 自動テスト

``` sh
composer phpunit
```

 詳細な品質基準は未定。

ステートメントカバレッジ85％程度が暫定目標。



※Laravelのバージョンアップ（8=>9）を予定しているため、自動テストによりスムーズにバージョンアップできると良い。

※packages/ 以下のテストはUnit

※app/ 以下のテストはFeature とUnit



## ミューテーション解析

詳細な品質基準は未定。

```sh
phpdbg -qrr vendor/bin/infection
```



# 評価指標の計測

以下コマンドにより指標を計測予定。

```
composer phpmetrics
```

詳細な品質基準は未定。



# 備考

コード品質に関する出力は、qaディレクトリ以下に行う。

- カバレッジレポート（phpunit）
- メトリクス（phpmetrics）
- ミューテーション解析ログ（infection）
- 静的解析の結果ログ（psalm）

等を予定。





以上