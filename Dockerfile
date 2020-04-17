FROM node:10 as build
WORKDIR /app
COPY . ./
RUN yarn install --network-timeout 1000000
RUN yarn build

FROM debian:buster

EXPOSE 80

ENV DEBIAN_FRONTEND noninteractive
ENV APP_HOME /var/www

WORKDIR ${APP_HOME}

RUN apt-get update -qq && \
    apt-get install -y --force-yes apt-transport-https lsb-release ca-certificates nginx curl wget openssl vim cron procps supervisor sudo \
    php7.3-cli php7.3-mysqlnd php7.3-curl php7.3-gd php7.3-sqlite php7.3-xml php7.3-dom php7.3-bcmath php7.3-fpm php7.3-gmp php7.3-mbstring php7.3-intl php-zmq php-pear git zip && \
    apt-get clean autoclean && \
    apt-get autoremove --yes && \
    rm -rf /var/lib/{apt,dpkg,cache,log}/

RUN curl --silent --show-error https://getcomposer.org/installer | php

ADD composer.json ${APP_HOME}/
ADD composer.lock ${APP_HOME}/

RUN php composer.phar install --optimize-autoloader && \
    rm composer.phar

ADD bin/ ${APP_HOME}/bin/
ADD config/ ${APP_HOME}/config/
COPY --from=build /app/public/ ${APP_HOME}/public/
ADD src/ ${APP_HOME}/src/
ADD templates/ ${APP_HOME}/templates/

ADD  docker/etc/ /etc/


RUN mkdir -p var/cache var/log && chown -R www-data:www-data var/

CMD	["/usr/bin/supervisord"]
