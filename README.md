# DockerでローカルPHPの環境構築をおこなう
Dockerとdocker-composeを使用して、PHP8、MySQL8、Nginxの環境を構築することは可能です。以下にそのための手順を示します。

まず、以下のようなフォルダ構成を作成します。
```
.
├── docker
│   ├── db
│   │   └── my.cnf
│   ├── nginx
│   │   └── default.conf
│   └── php
│       └── Dockerfile
├── html
│   └── index.php
└── docker-compose.yml
```

次に、各ファイルの内容を以下のように設定します。

1. docker-compose.yml:
```yaml
version: '3'
services:
  web:
    image: nginx:latest
    ports:
      - "8080:80"
    volumes:
      - ./html:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
      - db
  php:
    build: ./docker/php
    volumes:
      - ./html:/var/www/html
    depends_on:
      - db
  db:
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - ./docker/db/my.cnf:/etc/mysql/my.cnf
```

2. docker/php/Dockerfile:
```
FROM php:8-fpm
RUN docker-php-ext-install pdo_mysql
```

3. docker/nginx/default.conf:
```
server {
    listen 80;
    index index.php index.html;
    server_name localhost;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/html;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

4. docker/db/my.cnf:
```
[mysqld]
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci
```

5. html/index.php:
```php
<?php
echo "Hello, world!";
```

これらの設定を行った後、以下のコマンドを実行してDockerコンテナを起動します。
```bash
docker-compose up -d
```

Dockerコンテナを起動した後、ブラウザで以下のURLにアクセスすることで、作成したPHPページを表示することができます。

http://localhost:8080

## MySQLに接続する
はい、VS CodeのDocker拡張機能を使用して、MySQLのコンテナにシェルをアタッチすることができます。以下にその手順を示します。

VS Codeの左側のアクティビティバーにあるDockerアイコンをクリックします。
コンテナ一覧が表示されるので、MySQLのコンテナを右クリックします。
メニューから"Attach Shell"を選択します。
これにより、VS Codeの統合ターミナルにMySQLコンテナのシェルが表示されます。

以下のコマンドを入力するMySQLにログインできます。
```bash
mysql -u root -p
```

パスワードは`root`と入力する。

## データベースとテーブルの作成
MySQLでお問い合わせテーブルを作成するためのSQLコマンドは以下の通りです。ここでは、データベース名をmyDatabase、テーブル名をinquiriesとしています。

まず、データベースを作成します。
```
CREATE DATABASE myDatabase;
```

次に、作成したデータベースを選択します。
```
USE myDatabase;
```

そして、お問い合わせテーブルを作成します。テーブルにはid（主キー）、name（名前）、email（メールアドレス）、subject（件名）、message（メッセージ）の5つのカラムを設定します。
```sql
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL
);
```

テーブルの定義を確認する
```sql
DESC inquiries;
```

このようになっているはず。
```
mysql> DESC inquiries;
+---------+--------------+------+-----+---------+----------------+
| Field   | Type         | Null | Key | Default | Extra          |
+---------+--------------+------+-----+---------+----------------+
| id      | int          | NO   | PRI | NULL    | auto_increment |
| name    | varchar(255) | NO   |     | NULL    |                |
| email   | varchar(255) | NO   |     | NULL    |                |
| subject | varchar(255) | NO   |     | NULL    |                |
| message | text         | NO   |     | NULL    |                |
+---------+--------------+------+-----+---------+----------------+
5 rows in set (0.01 sec)
```