FROM php:8.2-cli
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite

WORKDIR /usr/src/app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

CMD ["php", "src/index.php"]