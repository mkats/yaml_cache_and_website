# Dockerfile

#
# This image contains:
#   - PHP 7
#   - PHP extensions:
#     - sockets
#     - yaml-2.0.0
#     - memcached
#   - apache2 service
#   - memcached service
#
# This Docker image is used as an intermediate image for mkats/yaml_cache_and_website-service.
#
# Build image using the command
# docker build -t mkats/php7-apache-with-exts ./php7-apache-with-exts
# 

FROM php:7.0-apache

MAINTAINER Michalis Katsarakis version: 0.1

RUN buildDeps=" \
    libyaml-dev \
	libz-dev libmemcached-dev \
    " \
    && set -x \
    && apt-get update \
	&& apt-get install -y \
		libyaml-0-2 \
		memcached \
		--no-install-recommends \
    && apt-get install -y $buildDeps --no-install-recommends \
	&& rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) \
		sockets \
	&& pecl channel-update pecl.php.net \
	&& pecl install yaml-2.0.0 < /usr/lib/x86_64-linux-gnu \
	&& pecl install memcached \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $buildDeps	\
	&& docker-php-ext-enable yaml \
	&& docker-php-ext-enable memcached \
	
	&& a2enmod rewrite \
	&& service apache2 restart
