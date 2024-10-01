# Usar a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Copiar todos os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Expor a porta 80 para o servidor web
EXPOSE 80
