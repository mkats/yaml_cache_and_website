# Dockerfile

#
# Build image using the command
# docker build -t mkats/service_php:v0.1 .
#

FROM php:7
ENV SERVICE_LSTN_ADDR=192.168.99.100
ENV SERVICE_LSTN_PORT=8989
MAINTAINER Michalis Katsarakis version: 0.1
COPY ./service_php ~/service_php
RUN pwd && \
	ls -la && \
	php ~/service_php/index.php