# Example application basic checkout system

Example project: rule based checkout price calculator.

It is based on [https://lumen.laravel.com/docs/5.7](Lumen micro-framework) and [https://hoa-project.net/En/Literature/Hack/Ruler.html](Hoa\Ruler) component for rule parsing.

It is not production ready software. It is created only as a demo software. You cannot use, reuse, change or copy any part of it for purpose other than skill evaluation.

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

Following fields are available in rule definition:

* user
  * user.email
  * user.countryCode
  * user.groups - list of groups that user belongs to
  * user.tags   - list of tags that are assigned to an user
  * user.created
  * user.lastLogin
  * user.lastPurchase
* product
  * product.name
  * product.categories
  * product.tags
  * product.price
  * product.attributes
* products - list of all products in the cart

There are also additional variables created during checkout:
* all_products_attribute_ids
* all_products_categories

TODO add more    


/rules endpoint supports following operations:

* get /rules to get list of rules
* get /rules/{id} to get specific rule
* post /rules - to create new rule
* put /rules/{id} to update specific rule
* delete /rules/{id} to remove rule 

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
