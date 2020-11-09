# Galley
Todo list.

## Description
This is an SPA application. 
It uses Laravel and React.

## Run test
1. Seed the database.
`php artisan db:seed`
2. Run all test
`php artisan test` 
> Before seed the database you have to have a database with migrations,
> so create it and run migrations `php artisan migrate`.

## Build assets
`npm run prod` or `yarn prod`

## Generate documentation
> Suppose you are in the project directory.
```
./vendor/bin/openapi --format yaml --output ./swagger/swagger-doc.yaml ./swagger/swagger.php app
```
