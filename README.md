# Discounter
* [Installation](#installation)
* [Run tests](#run-tests)
* [Run server](#run-server)
* [Exmple request](#example-request)
* [Debug assignment](Debug.md)

## Installation
#### Prerequisites
* PHP >= 7.2
* Composer

#### Mac / Linux
`make install`

#### Windows
`composer install`

## Run tests

#### Mac / Linux
`make tests`

#### Windows
PHPUnit: `bin/phpunit --testdox` \
Deptrac: `vendor/bin/deptrac` \
Code sniffer: `vendor/bin/phpcs -p`

## Run server

#### Mac / Linux
`make run`

#### Windows
`php -S localhost:8000 public/index.php`

## Example Request

> Product with ID 4 & 5 get 20% discount and there is a general volume discount that adds a free product when you buy 2.

POST localhost:8000/ 

```json
{
    "order": {
        "lines": [
            {
                "product_id": 1,
                "product_name": "Lasagne",
                "quantity": 1,
                "unit_price": 1000
            },
            {
                "product_id": 2,
                "product_name": "Pizza",
                "quantity": 2,
                "unit_price": 1000
            },
            {
                "product_id": 4,
                "product_name": "Pasta",
                "quantity": 1,
                "unit_price": 1000
            }
        ],
        "currency": "EUR"
    }
}
```
