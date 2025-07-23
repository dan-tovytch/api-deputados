Antes de acessar o docker rode este comando dentro da pasta api:

- cp .env.example .env

Em seguida volta para o diretorio raiz e rode o docker
- docker compose up --build

Após rodar o docker segue este passo a passo

API:

1° Acessa o container da api:
- docker exec -it api bash
- composer install
- php artisan key:generate
- chown -R www-data:www-data storage bootstrap/cache
- php artisan migrate


INTERFACE:

2° acessar o container vue:
- npm install
