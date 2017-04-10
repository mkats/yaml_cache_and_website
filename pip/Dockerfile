# Dockerfile

#
# Build image using the command
# docker build -t mkats/website_pip:v0.1 .
#

FROM php:7.0-apache

MAINTAINER Michalis Katsarakis version: 0.1

ENV WEBSITE_BASE_URL=http://192.168.99.100:8002/
ENV SERVICE_LSTN_ADDR=192.168.99.100
ENV SERVICE_LSTN_PORT=28989

COPY . /var/www/html
#COPY ./pip /var/www/html

RUN a2enmod rewrite && \
    service apache2 restart && \
#	pwd && \
#	ls -la && \
	chmod 777 uploads
