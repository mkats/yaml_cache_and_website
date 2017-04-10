# Dockerfile

#
# Build image using the command
# docker build -t mkats/yaml_cache_and_website-service ./service_php
#

FROM mkats/php7-with-exts

MAINTAINER Michalis Katsarakis version: 0.1

#RUN apt-get update && \
#    apt-get install -y libyaml-dev && \
#    apt-get install -y libyaml-0-2 && \
#    pecl channel-update pecl.php.net && \
#    pecl install yaml-2.0.0 < /usr/lib/x86_64-linux-gnu && \
#    docker-php-ext-enable yaml

#RUN apt-get install -y \
#        libmemcached-dev \
#        curl \
#    && curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
#    && mkdir -p /usr/src/php/ext/memcached \
#    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
#    && docker-php-ext-configure memcached \
#    && docker-php-ext-install memcached \
#    && rm /tmp/memcached.tar.gz \

#RUN apt-get install -y libz-dev libmemcached-dev --no-install-recommends \
#    && apt-get install -y memcached --no-install-recommends \
#    && pecl install memcached \
#    && docker-php-ext-enable memcached

ENV SERVICE_LSTN_PORT=28989
EXPOSE 28989

COPY . /usr/src/service_php
RUN chmod -R 777 /usr/src/service_php

CMD service memcached start \
    && php /usr/src/service_php/index.php