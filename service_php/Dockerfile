# Dockerfile

#
# Build image using the command
# docker build -t mkats/yaml_cache_and_website-service ./service_php
#

FROM mkats/php7-with-exts

MAINTAINER Michalis Katsarakis version: 0.1

ENV SERVICE_LSTN_PORT=28989
EXPOSE 28989

COPY . /usr/src/service_php
RUN chmod -R 777 /usr/src/service_php

CMD service memcached start \
    && php /usr/src/service_php/index.php