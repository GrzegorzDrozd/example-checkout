# Example application basic checkout system

Example project: rule based checkout price calculator.

## Requirements

Tested on: PHP 7.2.10.
Extensions: ext-json, ext-openssl, ext-mbstring 

## Installation
Clone project into a directory. Run composer install --dev.

## Testing

php -S 127.0.0.1:9999 -t public

# Usage

## Rule definition:

Example rules:
```json 
{
  "name": "darmowe okulary do teleizora 3d!",
  "rule": "3 in all_products_attribute_ids",
  "additionalProducts": [
    {"id": 5, "quantity": 1}
  ]
}
```

```json
{
  "name": "-10% na telewizor!",
  "rule": "in_path('RTV/Telewizor/*', product.categories)",
  "valueMultiplier": 0.9
}

```

Send post request to /rules/. Rule can have the following fields:

* name - rule name
* rule - rule text
* valueMultiplier - float
* valueModifier - int
* additionalProducts - product list
..* product: with id and quantity fields.

Rule grammar is based on [https://hoa-project.net/En/Literature/Hack/Ruler.html](https://hoa-project.net/En/Literature/Hack/Ruler.html)

To calculate cart value send post request to /checkout/ endpoint:

```json
{"products": [{"id": 1, "quantity": 1}], "user": 1}
```

Example response:

```json
{
  "price": 1000.99,
  "price_with_discounts": 900.89,
  "applied_rules": [
    "darmowe okulary do teleizora 3d!"
  ],
  "products": [
    {
      "id": 1,
      "quantity": 1,
      "applied_rules": [
        "-10% na telewizor!"
      ],
      "price_with_discount": 900.89,
      "price": 1000.99
    },
    {
      "id": 5,
      "quantity": 1
    }
  ]
}
```
