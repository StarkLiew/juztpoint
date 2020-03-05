## About JuztPoint
Am I crazy, release a project I working privately for past 6 months as opensource. JuztPoint is first full-fledged multi-tenant Point of Sale system release under opensource license in Malaysia. A Point of Sale (POS) build with Progressive Web App technology (PWA). Unlike other PWA point of sale, this PWA is built to feel like an native app. Currently this software is have been running under production testing at my own retail premise.

## Setup
### Production
$ docker-compose up -d
$ docker-compose exec juzt-app composer install
$ docker-compose exec juzt-app php artisan install
$ docker-compose exec juzt-app php artisan migrate
$ docker-compose exec juzt-app php artisan db:seed
$ docker-compose exec juzt-app php artisan key:generate


### Development

#### Backend
$ docker-compose up -d (to fire up mariadb)
$ composer install
$ php artisan install
$ php artisan migrate
$ php artisan db:seed
$ php artisan key:generate
$ php artisan run serve

#### Frontend
$ npm run dev 

or (live)

$ npm run watch 

### Optional
Edit the host file as follow (for localhost)
127.0.0.1  www.domain.com app.domain.com backoffice.domain.com


## Release Plan based on Sector Support
### Version 1:
- Retail
- Salon
- Car Wash

### Next Version Sector Support:
- F&B sector
- more...

## Features to work on

### Backend
1. Broadcast Notification
2. Strengthen api security

### BackOffice
1. Receive Notification
2. More reports
3. Internal Use (Issue Inventory)
4. Plugin

### Frontned
1. Thermal Printing (Working on)
2. Sync Open/Close Shift
3. Receive Notification
4. Software Update Refresh Notification
4. Update Refresh Notification

... and many more bugs fix.


## Requirement
- docker
- docker-compose
- linux, centos, window
- frontend - any pc, mobile and tablet that support PWA browser

## Installation
- $ docker-compose exec app php artisan key:generate
- $ docker-compose exec app php artisan config:cache
- $ docker-compose exec app php artisan migrate
- $ docker-compose exec app php artisan db:seed

## Development
- $ docker-compose -f docker-compose-dev.yml up -d
- $ npm run dev / npm run watch

## Production
$ docker-compose up -d

## Environment File
$ docker-compose exec app nano .env


## Documentation

Planing for a seperate github reposities for communities written user manual.

## Sponsors

I would like to extend my thanks to any future sponsors for funding JuztPoint development.

The sponsors are:
1. pending

## Contributing

Thank you for considering contributing. 

## License
Apache License 2.0

