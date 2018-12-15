# online judge 2
## 概要
競技プログラミングに使用できるオンラインジャッジシステムです.
~~データベースその他に[旧OJ](https://gitbucket.ibuki2003.yokohama/ibuki2003/online_judge)と互換性をもたせています.~~
旧OJと置き換えが可能です.

## 構築手順
1. 任意のディレクトリにこれをcloneします.
2. `composer install`を実行します.
3. `.env`ファイルを`.env.example`からコピーし,自身の環境に合わせて編集します.
4. `php artisan migrate`を実行します.
5. langsテーブルに任意の言語を追加します.
   詳しくは下の説明を参照してください.
6. `public/`をドキュメントルートとしてWebサーバーを起動します.
7. ジャッジプログラムを起動します.

### 旧OJとの互換性
データベースの操作をすることなく新OJに置き換えて使用することができます.上記手順3にて旧OJで使用していたデータベースの設定を使用してください.

## 言語の追加方法
langsテーブルに行を挿入することで追加が可能です.
それぞれの列は以下の役割があります
- id
    - 内部でのID
    - キーに使用されるため,uniqueである必要がある.
- name
    - 表示名.送信画面などUIで表示される
- extension
    - 拡張子,`source.[拡張子]`でソースが保存される
- compile
    - コンパイルコマンド
    - インタプリタ言語など,不要の場合はNULL
- exec
    - 実行コマンド

コマンド中のファイルにはパスを指定する必要があります.
ファイルパス部分を`{path}`としてください.

## 必要なライブラリ,ソフトウェア
- Webサーバ
    - PHPの動作が可能であること.
    - Apache推奨
    - nginxなどを使う場合は`.htaccess`を参考に設定をする必要あり.
- PHP
    - Versionは7以上推奨
    - DBその他のextensionが有効であること
- Composer
    - ライブラリのインストールに必要
- ジャッジプログラム
    - [oj_judger](/ibuki2003/oj_judger)などが使用できます.

## ライセンス
このソフトウェアはMITライセンスのもとで公開されています.
その他,以下のライブラリを使用しています.

- [KaTeX](https://github.com/KaTeX/KaTeX)
  > KaTeX is licensed under the MIT License.
