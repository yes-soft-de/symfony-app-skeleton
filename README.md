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

Request:
    - userID
    - password
    - email
    - userName
```
#### login
```
/login_check
methods: POST

Request:
    - username
    - password
```
#### Update user profile
```
/api/userprofile
methods: PUT

Request:
    - userName
    - city
    - story
    - image
    - date
    - dateAndTime
```
#### Get user profile by userID
```
/api/userprofile
methods: GET

Response:
    - userName
    - city
    - story
    - image
    - date
    - dateAndTime
```
#### Get all profiles of all users
```
/api/userprofile
methods: GET

Response:
 array of objects, each one contains the following:
    - userName
    - city
    - story
    - image
    - date
    - dateAndTime
```