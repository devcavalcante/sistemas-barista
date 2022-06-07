# Barista

Essa é uma api para ser utilizada na disciplina de Sistemas distribuidos, consiste
na criação de um sistema onde será registrado os bares da cidade de Santarém-PA
e suas informações relevantes. O sistema está hospedado no heroku e a api
pode ser utilizada através deste link: ()

## Documentação
Todo projeto está sendo documento com o swagger, a documentação atualizada
pode ser acessada pelo link: ()

## Rodando o projeto
Para rodar o projeto na sua máquina, basta ter o docker instalado e rodar os seguintes comandos:
1. docker-compose build
2. docker-compose up -d
3. docker exec -it sistemas-barista_web_1 composer install
4. docker exec -it sistemas-barista_web_1 php artisan passport:install

## Gerar documentação com o swagger
1. docker exec -it sistemas-barista_web_1 php artisan swagger-lume:generate 
2. acesse no link (http://localhost:8001/api/documentation)
