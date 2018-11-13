# online judge 2
## 概要
競技プログラミングに使用できるオンラインジャッジシステムです.
データベースその他に[旧OJ](https://gitbucket.ibuki2003.yokohama/ibuki2003/online_judge)と互換性をもたせています.
## 使い方
1. 任意のディレクトリにcloneします.
2. `composer install`を実行します.
3. `.env`ファイルを`.env.example`からコピーし,自身の環境に合わせて編集します.
4. `php artisan migrate`を実行します.
5. `public/`をドキュメントルートとしてWebサーバーを起動します.

### 注意
ジャッジプログラムは現在旧OJに付属した形で存在しています.
これ単体で動作させる場合は旧OJよりジャッジプログラムを持ってくる必要があります.

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

## ライセンス
このソフトウェアはMITライセンスのもとで公開されています.
その他,以下のライブラリを使用しています.

- [KaTeX](https://github.com/KaTeX/KaTeX)
  > KaTeX is licensed under the MIT License.
