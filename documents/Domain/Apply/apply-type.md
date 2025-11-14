# 申出種別（apply_type）
作成:2023-02-05

以下の記載は、すべて2023-02-05時点における内容である点に留意すること。

# 概要
4つの申出種別について、新規参加する開発者向けに説明する資料です。

# 詳細
全国がん情報の利用については、その申出者、利用形態によって
- 適用される法令が異なる
- 申出における提出文面、添付文書の種類が異なる

などの違いがあるため、申出がこれらのどの区分に該当するかを区別する必要がある。

## 申出種別（apply_type）の種類
以下の4種類が存在する。
- 1.GOVERNMENT_LINKAGE 行政関係者・リンケージ利用
- 2.GOVERNMENT_STATISTICS 行政関係者・集計統計利用
- 3.CIVILIAN_LINKAGE 研究者等・リンケージ利用
- 4.CIVILIAN_STATISTICS 研究者等・集計統計利用

これらの種別のリストは、以下にクラスとして定義されている。
[https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Classification/ApplyTypes.php](https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Classification/ApplyTypes.php)

また、申出種別に関する判断は、下記のクラスにて実装されている。
[https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Entity/ApplyType.php](https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Entity/ApplyType.php)


## 申出者の種別
全国がん情報を利用しようとする申出者については、以下の2種類を区別して取り扱う必要がある。
- 行政関係者(GOVERNMENT)
- 民間の研究者など(CIVILIAN)

### 行政関係者
- 行政関係者への全国がん情報提供は、がん登録等の推進に関する法律（平成25年法律第111号）第17条　の規定が適用される。
- ファイル名等に「17」と付いている場合、行政関係者であることを示すことがある

### 民間の研究者など
- 民間の研究者などへの全国がん情報提供は、がん登録等の推進に関する法律（平成25年法律第111号）第21条 の規定が適用される。
- ファイル名等に「21」と付いている場合、研究者であることを示すことがある
 
## 利用形態
全国がん情報の利用形態として、以下の2種類を区別して取り扱う必要がある。
- 顕名（LINKAGE）
- 匿名（STATISTICS）

### 顕名（リンケージ / LINKAGE）
- 顕名（リンケージ）とは、個人情報と紐づいた形での全国がん情報の提供を求める申出である。
- 患者個人の年齢、性別、居住地等の具体的情報が必要な場合、この区分に該当する（たぶん）

### 匿名（集計統計 / STATISTICS）
- 匿名（集計・統計）とは、個人情報と紐づかない形での全国がん情報の提供を求める申出である。
- 都道府県ごとの患者数、年齢区分ごとの男女比などが、この区分に該当する（たぶん）

## 申出種別による違い
申出種別によりどのような差異があるのか、すべてを記載することはできないが、以下のような例が挙げられる。
 - 添付しなければならない資料の種類
   - 以下のクラス内に、必須か任意か、が一覧となっている
   - [https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Classification/AttachmentTypes.php](https://github.com/git-balocco/ncc01/blob/main/packages/Apply/Enterprise/Classification/AttachmentTypes.php)
 - 記入項目、出力PDFの違い
   - 申請種別により、記入項目が異なる場合があり、各種別ごとのテンプレートが用意されている。
   - また、PDFの出力内容も一部異なる場合があり、各種別ごとのテンプレートが用意されている。
   - 各種別のテンプレート格納先は、設定ファイル [https://github.com/git-balocco/ncc01/blob/main/config/app-ncc01.php](https://github.com/git-balocco/ncc01/blob/main/config/app-ncc01.php) に定義されている。
   - 申出入力のテンプレート群 [https://github.com/git-balocco/ncc01/tree/main/resources/views/contents/apply/detail](https://github.com/git-balocco/ncc01/tree/main/resources/views/contents/apply/detail)
   - PDFのテンプレート群 [https://github.com/git-balocco/ncc01/tree/main/resources/views/pdf/apply](https://github.com/git-balocco/ncc01/tree/main/resources/views/pdf/apply)


以上
