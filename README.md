## Requirement

- PHP >= 8.1
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySql
- MongoDB / Redis
- [Composer](https://getcomposer.org/download/)
- nodejs
- npm
- yarn

## How to install

- clone repository to your server
- then enter to directory app `cd liniswara`
- copy `.env.example` as `.env`
- re-configuration the config of url, database, cache and disk etc.
- run command `composer install` for install vendor
- run command `yarn install` to install plugins
- run command `yarn run prod` to compile file css
- run command `php artisan migrate --seed` to migrate database and default value of the record.
- run command `php artisan storage:link` if the storage set on local
