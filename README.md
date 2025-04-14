# API de Clientes

## Requisitos
Esse projeto utiliza [docker v27.4.x](https://docs.docker.com/) e [docker compose](https://docs.docker.com/compose/), certifique-se de que está utilizando a [versão mais recente](https://docs.docker.com/install).

## Configurando o seu ambiente.

Depois de ter instalado e configurado o docker compose, siga os passos abaixo no seu terminal:

1. `git clone https://github.com/leonardofr97/Teste-Dev-php`
2. `cd customer-api`
3. `cd docker`
4. `docker compose up -d`

## Configurando o projeto

Depois de ter configurado o seu ambiente, nós vamos configurar o projeto, siga os passos abaixo:

1. `docker compose exec --user=php-user api bash`
2. `cp .env.example .env`
3. `composer install`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed` *(esse passo não é obrigatório)*

## Endpoints
Disponibilizei uma `collection` do Postman em `/docs`.

`GET` /customers
  - filters[document][eq]=06757467724 -- CPFs iguais (eq) ao valor indicado.
  - filters[name][inc]=ANTONIETA -- Nomes que incluem (inc) a string indicada.
  - filters[zip_code][eq]=909471601 -- CEPs iguais (eq) ao valor indicado.
  - per_page=5
  - page=1

`POST` /customers

`PATCH` /customers

`DELETE` /customers

## Melhorias
- [x]  Armazenar dados do endereço em cache em memória;
- [ ]  Armazenar dados de endereço a partir do CEP direto no banco de dados e utilizar cache físico;
- [ ]  Adicionar soft-delete e rotina para remoção automática dos dados baseado em tempo pré-definido;
- [ ]  Testes unitários/integração.
- [ ]  Adicionar autenticação.