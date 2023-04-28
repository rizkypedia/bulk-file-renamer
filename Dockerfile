FROM php:8.1-cli
# Set working directory
WORKDIR /var/www
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install zip
RUN apt-get update \
     && apt-get install -y zip \
     && pecl install xdebug-3.1.4 \
     && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Add symlink to the executable
RUN ln -s /var/www/pnv /usr/local/bin/pnv
# Add user
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Change current user to www
USER www
