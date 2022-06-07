FROM php:8.0-fpm-alpine

ARG XDEBUG_MODE
ENV XDEBUG_MODE $XDEBUG_MODE
ARG XDEBUG_IDEKEY
ENV XDEBUG_IDEKEY $XDEBUG_IDEKEY
ARG XDEBUG_HANDLER
ENV XDEBUG_HANDLER $XDEBUG_HANDLER
ARG XDEBUG_PORT
ENV XDEBUG_PORT $XDEBUG_PORT

RUN apk add --no-cache openssl bash nodejs npm postgresql-dev
RUN docker-php-ext-install bcmath pdo pdo_pgsql

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www

RUN chmod -R 777 /var/www/storage

RUN if [ "$APP_STAGE" == "dev" ]; then \
    apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug && \
    echo "xdebug.mode=$XDEBUG_MODE" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_handler=$XDEBUG_HANDLER" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=$XDEBUG_IDEKEY" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \

    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_port=$XDEBUG_PORT" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \

    docker-php-ext-enable xdebug; \
fi

EXPOSE 9000

ENTRYPOINT [ "php-fpm" ]
