FROM php:8.0-apache

RUN apt-get update && apt-get install -y libbz2-dev zlib1g-dev libpng-dev libgmp-dev libicu-dev \
    libsnmp-dev libtidy-dev libxml2-dev libxslt-dev libzip-dev libmagickwand-dev --no-install-recommends

RUN apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install -j$(nproc) imap

RUN docker-php-ext-install -j$(nproc) bcmath bz2 calendar exif gd gmp intl mysqli \
    opcache pdo pdo_mysql snmp tidy xmlwriter xml xsl zip

RUN pecl install imagick

RUN docker-php-ext-enable imagick

RUN a2enmod rewrite vhost_alias

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV TZ=Europe/Prague
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN sed -i "s/post_max_size = .M/post_max_size = 250M/ ; s/upload_max_filesize = .M/upload_max_filesize = 250M/" /usr/local/etc/php/php.ini
RUN echo "date.timezone = Europe/Prague" > /usr/local/etc/php/conf.d/timezone.ini
