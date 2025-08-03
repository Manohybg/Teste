FROM php:8.1-apache
RUN apt-get update && apt-get install -y libcurl4-openssl-dev
COPY . /var/www/html/
EXPOSE 80
