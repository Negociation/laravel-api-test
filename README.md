# Backend Challenge 20230105
- Desafio rabalhado em PHP com Laravel, em um ambiente dockerizado para testes.

## Dependencias Necessárias (Web)
 
- Sevridor Web (Apache, Nginx...)
- MongoDb Habilitado no servidor Web
- OpenSSL habilitado

## Dependencias Necessárias (Docker)
- Recomendo o uso da Docker que ja esta configurada para uso com o Redis (Cache)
- Necessario a execução do Composer Install dentro da Docker

### Comandos:
- Executar CRON manualmente sem precisar configurar o Crontab (php artisan app:api-product-import)
- Executar testes unitarios (php artisan test)

### Variaveis de Ambiente 
As variaveis se encontram no env.example, bastando renomear para .env
- APP_URL (Colocar Porta se estiver personalizada ou da Docker)
- API_KEY (Token Bearier para autenticação na API)
- DB_URI (URI de conexão com o MongoDB Atlas)
- REDIS_HOST=redis
- REDIS_PASSWORD=null
- REDIS_PORT=6379


### Executar na Docker
- Executar: docker-compose up -d
- Acessar a docker: docker exec -it backend bash 
- Instalar dependencias: composer install 

### Cuidado com os testes ###
Se estiver rodando o comando de testes fora da Docker, o redis ira falhar devido a falta de conexão.

### Documentação da API ####
Documentação base da API se encontra já gerada no formato json na pasta public (swagger.json)
- Gerar uma nova documentação atualizada:  php artisan swagger:generate

