FROM php:8.2-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar o código da aplicação
COPY ./backend /var/www/html

# Instalar dependências do Laravel
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# Expor porta do Laravel
EXPOSE 8000

# Comando padrão
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]