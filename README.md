# AI JSON API

Simple REST API using vanilla PHP and JSON storage.

## Run with Docker

docker-compose up --build

## Run locally

php -S localhost:8000 -t public

## Endpoints

GET /customers

POST /customers

GET /customers/{id}

PUT /customers/{id}

DELETE /customers/{id}

## Try it out

curl -X POST http://localhost:8000/customers   -H "Content-Type: application/json"   -d '{"first_name":"Petar", "last_name":"Petrovic"}'
