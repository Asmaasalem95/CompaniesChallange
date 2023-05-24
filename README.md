## About
A single page Application That List  and filter Companies Data .

### Technologies
laravel Framework with Vuejs


 ### Installation

run `git clone https://github.com/Asmaasalem95/CompaniesChallange`

Build The container `docker-compose up -d`

Enter The container  `sudo docker exec -it app /bin/sh
`
run `composer install`

run `npm install`

copy  `.env.example file to .env `

generate new app key `php artisan key:generate`


navigate to your public server to see the app 

### Errors You May Face
1. storage folder permission denied `chmod 777 -R /storage`


### Test 

`php artisan test`
