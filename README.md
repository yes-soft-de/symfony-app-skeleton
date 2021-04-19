# Symfony App Skeleton 🚧
*. env file and private-public keys not enclosed .*
## Project setup

### Composer thing
```
composer update
```
### Database setup
First add to** .env** file correct connection string
`DATABASE_URL=mysql://root@127.0.0.1:3306/animeDB?serverVersion=5.7`

Then create database
```
php bin/console doctrine:database:create
```

After that make migration
```
php bin/console make:migration
```

Finaly run migration versions to create tables
```
php bin/console doctrine:migration:migrate
```

### Account
#### Create new user
```
/api/user
methods: POST
```
#### login
```
/login_check
methods: POST
```
#### Update user profile
```
/api/userprofile
methods: PUT
```
#### Get user profile by userID
```
/api/userprofile
methods: GET
```
