# Dockerfile

#
# This image contains:
#   - PHP 7
#   - memcached service
#   - PHP extensions:
#     - sockets
#     - yaml-2.0.0
#     - memcached
#
# This Docker image is used as an intermediate image for mkats/yaml_cache_and_website-service.
#
# Build image using the command
# docker build -t mkats/php7-with-exts ./php7-with-exts
# 

FROM php:7

MAINTAINER Michalis Katsarakis version: 0.1

RUN buildDeps=" \
#    g++ \
#    libicu-dev \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#    libmcrypt-dev \
#    libpng12-dev \
#    libpq-dev \
#    libssl-dev \
#    libtidy-dev \
#    libxslt1-dev \
    libyaml-dev \
	libz-dev libmemcached-dev \
#    ncurses-dev \
#    unzip \
    " \
    && set -x \
    && apt-get update \
	&& apt-get install -y \
#		bzip2 curl fontconfig git libc6 libcomerr2 libfontconfig1 libfreetype6 libgcc1 \
#		libmcrypt4 libgcrypt20 libgpg-error0 libicu52 libjpeg62-turbo libkeyutils1 liblzma5 \
#		libpng12-0 libpq5 libreadline6 libtidy-0.99-0 libtinfo5 libwebp5 libxslt1.1 \
		libyaml-0-2 \
		memcached \
#	    mysql-client postgresql-client sqlite3 \ 
#   	sudo supervisor zlib1g \
		--no-install-recommends \
    && apt-get install -y $buildDeps --no-install-recommends \
#	&& rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) \
#		bcmath exif ftp gettext intl mcrypt mbstring mysqli opcache pcntl pdo_mysql pdo_pgsql pgsql shmop soap \
		sockets \
#		sysvsem sysvshm tidy wddx xmlrpc xsl zip \
#    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#    && docker-php-ext-install -j$(nproc) gd \
	&& pecl channel-update pecl.php.net \
#    && echo | pecl install channel://pecl.php.net/APCu-4.0.7 \
#    && echo | pecl install xdebug \
	&& pecl install yaml-2.0.0 < /usr/lib/x86_64-linux-gnu \
	&& pecl install memcached \
#    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $buildDeps	\
	&& docker-php-ext-enable yaml \
	&& docker-php-ext-enable memcached

