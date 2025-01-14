FROM php:8.2.1-fpm
# Cài đặt các tiện ích cần thiết
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev

# Cài đặt các phần mở rộng PHP cần thiết
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl intl gd
# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Tạo thư mục làm việc
WORKDIR /var/www/html
# Sao chép mã nguồn vào container
COPY . .
# Cài đặt các gói PHP
RUN composer install
# Thiết lập quyền cho thư mục lưu trữ
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]