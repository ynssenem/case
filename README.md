## Services

- MySQL 8.0
- Apache
- PHP7-FPM 7.4

## Installation
1. Run docker compose:
```sh
$ docker-compose up -d
```

2. Visit http://127.0.0.1:8080/
![Symfony 5 localhost](.doc/firstImage.png)

3. Install additional php dependencies: 
```sh
$ docker-compose exec php composer install
```

4. Run migration:
```sh
$ docker-compose exec php bin/console d:m:m
```

5. Run Lexik command:
```bash
$ docker-compose exec php bin/console lexik:jwt:generate-keypair
```

## Create User

1. Connect Mysql Container:
```bash
$ docker-compose exec mysql mysql -u root -p
## RootPassword "root"
```

2. Select Database:
```bash
use my_database
```

3. Run Commands (All users password is 000000):
```sql
insert into user (email, roles, password) VALUES ('provider_one@example.com', '["ROLE_USER"]', '$2y$13$pSMY5gKkV9tKQxhQYTJmNemECw/YbhDSkWH1G5b.u.cO37C85761i');
insert into user (email, roles, password) VALUES ('provider_two@example.com', '["ROLE_USER"]', '$2y$13$pSMY5gKkV9tKQxhQYTJmNemECw/YbhDSkWH1G5b.u.cO37C85761i');
insert into user (email, roles, password) VALUES ('provider_three@example.com', '["ROLE_USER"]', '$2y$13$pSMY5gKkV9tKQxhQYTJmNemECw/YbhDSkWH1G5b.u.cO37C85761i');
```

## How To Use Api

You can use it using the [Postman Collection](.doc/PATH.postman_collection.json) if you wish. `{{api}}` in examples `http://localhost:8080`

### Login

---

1. Generating Tokens And Logging In:
```bash
$ curl --location -g --request POST '{{api}}/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "username":"provider_one@example.com",
    "password":"000000"
}'
```
Success Response:
```bash
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDU5NzU2NTgsImV4cCI6MTY0NTk3OTI1OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoicHJvdmlkZXJfdGhyZWVAZXhhbXBsZS5jb20ifQ.hBm5erLDAcZJ0s5NxuG0jr03-h-s9AoMBFBczB6pQEK9KwT2hK-lPPHGcv2nbqi0lLoFg-Gz3ebHu1HLW13PWMB6VrzbbkEvHC4NGq30IHGPF643jh5wmNUBD8-zImwdYfP7bkrrtE3_-TroSFaiwws0kkqU28gHuAm0F2yxi6MU0Cr2toiiW1gLkjzc3XsqCQAISLaT3GdzA1HlSxuh1fmxNzRIY9yf6wzB1c9nMDbYbHBRYUsa9NxyFX9JH4XZjm-ovzKkiWxmKnUvG8k_i8yZm6je2RLAM-Vq9sNacYOfr95NDiZnHH66VhgagiiMxfR-mAcXBzWwZCwVrbNyBw"
}
```

### Create Order

---

To create a new order, you need to add the web token you received from the login client to the header.

Example Create Order Schema:
```
{
    "orderCode": "One1122334420", // Provider Code
    "productId": 112233, // Provider/ABC ProductID
    "quantity": 1, 
    "address": "Lorem ipsum dolor sit amet, consectetur adipiscing elit",
    "shippingDate": "2022-01-01 01:01:01" // Optionel
}
```

Example Request:
```bash
$ curl --location -g --request PUT '{{api}}/api/order' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDU5NzU2NTgsImV4cCI6MTY0NTk3OTI1OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoicHJvdmlkZXJfdGhyZWVAZXhhbXBsZS5jb20ifQ.hBm5erLDAcZJ0s5NxuG0jr03-h-s9AoMBFBczB6pQEK9KwT2hK-lPPHGcv2nbqi0lLoFg-Gz3ebHu1HLW13PWMB6VrzbbkEvHC4NGq30IHGPF643jh5wmNUBD8-zImwdYfP7bkrrtE3_-TroSFaiwws0kkqU28gHuAm0F2yxi6MU0Cr2toiiW1gLkjzc3XsqCQAISLaT3GdzA1HlSxuh1fmxNzRIY9yf6wzB1c9nMDbYbHBRYUsa9NxyFX9JH4XZjm-ovzKkiWxmKnUvG8k_i8yZm6je2RLAM-Vq9sNacYOfr95NDiZnHH66VhgagiiMxfR-mAcXBzWwZCwVrbNyBw' \
--header 'Content-Type: application/json' \
--data-raw '{
    "orderCode": "One1122334420",
    "productId": 112233,
    "quantity": 1,
    "address": "Lorem ipsum dolor sit amet, consectetur adipiscing elit"
}'
```

### Update Order

---

To update an order, you need to add the web token you received from the login client to the header.

Example Update Order Schema:
```json
{
    "orderId": 11,
    "shippingDate": "2022-01-01 01:01:01"
}
```

Example Request:
```bash
$ curl --location -g --request PATCH '{{api}}/api/order' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDU5NzU2NTgsImV4cCI6MTY0NTk3OTI1OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoicHJvdmlkZXJfdGhyZWVAZXhhbXBsZS5jb20ifQ.hBm5erLDAcZJ0s5NxuG0jr03-h-s9AoMBFBczB6pQEK9KwT2hK-lPPHGcv2nbqi0lLoFg-Gz3ebHu1HLW13PWMB6VrzbbkEvHC4NGq30IHGPF643jh5wmNUBD8-zImwdYfP7bkrrtE3_-TroSFaiwws0kkqU28gHuAm0F2yxi6MU0Cr2toiiW1gLkjzc3XsqCQAISLaT3GdzA1HlSxuh1fmxNzRIY9yf6wzB1c9nMDbYbHBRYUsa9NxyFX9JH4XZjm-ovzKkiWxmKnUvG8k_i8yZm6je2RLAM-Vq9sNacYOfr95NDiZnHH66VhgagiiMxfR-mAcXBzWwZCwVrbNyBw' \
--header 'Content-Type: application/json' \
--data-raw '{
    "orderId": 11,
    "shippingDate": "2022-01-01 01:01:01"
}'
```
### Get Order / Orders

---

To get an order or all orders, you need to add the web token you received from the login client to the header.

Example Request For A Order:
```bash
$ curl --location -g --request GET '{{api}}/api/getOrder?orderId=11' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDU5NzU2NTgsImV4cCI6MTY0NTk3OTI1OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoicHJvdmlkZXJfdGhyZWVAZXhhbXBsZS5jb20ifQ.hBm5erLDAcZJ0s5NxuG0jr03-h-s9AoMBFBczB6pQEK9KwT2hK-lPPHGcv2nbqi0lLoFg-Gz3ebHu1HLW13PWMB6VrzbbkEvHC4NGq30IHGPF643jh5wmNUBD8-zImwdYfP7bkrrtE3_-TroSFaiwws0kkqU28gHuAm0F2yxi6MU0Cr2toiiW1gLkjzc3XsqCQAISLaT3GdzA1HlSxuh1fmxNzRIY9yf6wzB1c9nMDbYbHBRYUsa9NxyFX9JH4XZjm-ovzKkiWxmKnUvG8k_i8yZm6je2RLAM-Vq9sNacYOfr95NDiZnHH66VhgagiiMxfR-mAcXBzWwZCwVrbNyBw'
```

Example Request For All Orders
```bash
$ curl --location -g --request GET '{{api}}/api/getOrders' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDU5NzU2NTgsImV4cCI6MTY0NTk3OTI1OCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoicHJvdmlkZXJfdGhyZWVAZXhhbXBsZS5jb20ifQ.hBm5erLDAcZJ0s5NxuG0jr03-h-s9AoMBFBczB6pQEK9KwT2hK-lPPHGcv2nbqi0lLoFg-Gz3ebHu1HLW13PWMB6VrzbbkEvHC4NGq30IHGPF643jh5wmNUBD8-zImwdYfP7bkrrtE3_-TroSFaiwws0kkqU28gHuAm0F2yxi6MU0Cr2toiiW1gLkjzc3XsqCQAISLaT3GdzA1HlSxuh1fmxNzRIY9yf6wzB1c9nMDbYbHBRYUsa9NxyFX9JH4XZjm-ovzKkiWxmKnUvG8k_i8yZm6je2RLAM-Vq9sNacYOfr95NDiZnHH66VhgagiiMxfR-mAcXBzWwZCwVrbNyBw'
```
