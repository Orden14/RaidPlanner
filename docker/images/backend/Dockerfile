FROM ubuntu

USER root

RUN apt-get update

RUN export DEBIAN_FRONTEND=noninteractive
RUN ln -fs /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN apt-get install -y tzdata
RUN dpkg-reconfigure --frontend noninteractive tzdata

RUN echo "Europe/Paris" | tee /etc/timezone
RUN dpkg-reconfigure --frontend noninteractive tzdata

RUN apt-get update && \
    apt-get install -y \
    apt-transport-https \
    software-properties-common \
    lsb-release \
    ca-certificates \
    curl \
    sudo \
    nano \
    wget \
    git \
    cron

RUN apt-get update

RUN add-apt-repository ppa:ondrej/php

RUN apt-get update && \
    apt-get install -y \
    php8.3-fpm \
    php8.3-mysql \
    php8.3-gd \
    php8.3-intl \
    php8.3-curl \
    php8.3-mbstring \
    php8.3-zip \
    php8.3-ldap \
    php8.3-xml \
    php8.3-dev

RUN service php8.3-fpm start

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

COPY www.conf /etc/php/8.3/fpm/pool.d/www.conf

RUN echo "www-data ALL=NOPASSWD : ALL" > /etc/sudoers

RUN deluser www-data
RUN useradd --no-log-init -u 1000 www-data

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash
RUN apt-get install nodejs
RUN npm install --global yarn
RUN mkdir /home/www-data && chown -R www-data:www-data /home/www-data

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

COPY entrypoint.sh /usr/bin/entrypoint.sh
RUN chmod +x /usr/bin/entrypoint.sh
ENTRYPOINT entrypoint.sh

USER www-data
WORKDIR /var/www/html
