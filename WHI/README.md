# About WHI?
![https://img.shields.io/badge/Laravel-8.77.1-red](https://img.shields.io/badge/composer-2.2.1-black)
![https://img.shields.io/badge/Laravel-8.77.1-red](https://img.shields.io/badge/Laravel-8.77.1-red)
![https://img.shields.io/badge/Laravel-8.77.1-red](https://img.shields.io/badge/npm-8.3.0-blue)
![https://img.shields.io/badge/Laravel-8.77.1-red](https://img.shields.io/badge/Vue-2.6.12-bluegreen)


## Backend

### 基本的な構造
![https://whi.s3.amazonaws.com/asset/WHI%3FBackend.svg](https://whi.s3.amazonaws.com/asset/WHI%3FBackend.svg)

*なぜServiceを設けたか*
Modelはデータベースのテーブルの状態や依存関係の実装を行うだけにとどめ、ServiceにBackendの実装を行ったほうがデータベースとビジネスロジックが分離でき、コードが分かりやすくなるほかにテストも行いやすくなると思ったから。

### 開発方法
1. `app/service`でビジネスロジックを実装したクラスを作成
2. `tests/Unit/`で作成したクラスのテストを行う
3. `app/Http/Controllers/User/`で作成したクラスを組み込むコントローラーを作成
4. `tests/Feature/User/`で作成したコントローラーに対して外部からのリクエストを想定したテストを実行

### 使用している外部サービス
[mailgun](https://www.mailgun.com/)
[AmazonS3](https://aws.amazon.com/jp/s3/)

## Frontend

### 使用している外部コンポーネント

## Database

### ER図
![https://whi.s3.amazonaws.com/asset/WHI_+ER+diagram.svg](https://whi.s3.amazonaws.com/asset/WHI_+ER+diagram.svg)

