# Dockerfile

#
# Build image using the command
# docker build -t mkats/yaml_cache_and_website .
#

FROM mkats/php7-apache-with-exts

MAINTAINER Michalis Katsarakis version: 0.1

ENV SERVICE_LSTN_PORT=28989

# Copy website
COPY ./pip /var/www/html
RUN chmod 777 /var/www/html/uploads

# Copy PHP script
COPY ./service_php /usr/src/service_php
RUN chmod -R 777 /usr/src/service_php \
	&& mv /usr/src/service_php/service_php.sv.conf /etc/supervisor/conf.d/service_php.sv.conf
