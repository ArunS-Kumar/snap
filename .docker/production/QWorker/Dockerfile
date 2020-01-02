FROM php:7.1.19-cli

ARG GITHUB_TOKEN

COPY .docker/production/php.ini /usr/local/etc/php/conf.d/php.ini

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN apt-get update && \
    apt-get upgrade -y

#Installing GIT
RUN apt-get install -y git

#Installing ZIP and CURL extensions for PHP
RUN apt-get install -y \
        libzip-dev \
        zip \
        curl \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

#Installing PGSQL extension for PHP
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

#Installing GD extension for PHP
RUN apt-get update && apt-get install -y libpng-dev && docker-php-ext-install gd

#Installing MCRYPT extension for PHP
RUN apt-get update && apt-get install -y libmcrypt-dev && docker-php-ext-install mcrypt

#Installing Cron and Supervisor for background jobs
RUN apt-get install -y cron && \
    apt-get install -y supervisor

#Copy Laravel source code to the image
COPY . /srv/app

#Resolve Laravel composer dependencies
RUN cd /srv/app && \
    composer config -g github-oauth.github.com $GITHUB_TOKEN && \
    composer update --no-dev

#Copy cron tab file to the image
COPY .docker/production/QWorker/crontab /etc/cron.d/crontab
RUN chmod 644 /etc/crontab && crontab /etc/cron.d/crontab

#Copy supervisor configuration to the image
COPY .docker/production/QWorker/laravel-worker.conf /etc/supervisor/conf.d/

#Copy run.sh file which should contain any command to be exeuted after running image
COPY .docker/production/QWorker/run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh

CMD ["run.sh"]