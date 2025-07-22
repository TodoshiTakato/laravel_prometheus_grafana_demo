FROM php:8.3-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

ARG USER=ns
ARG UID=1000

WORKDIR /var/www/html

# Install dependencies and extensions
RUN set -x && \
    chmod +x /usr/local/bin/install-php-extensions && \
    apk add --no-cache \
        supervisor \
        nano \
        curl \
        fcgi \
    && install-php-extensions \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        @composer \
    # Create user with same UID as host
    && addgroup -S ${USER} && \
    adduser -S ${USER} -G www-data -h /var/www/html -u ${UID} && \
    adduser ${USER} root && \
    # Create supervisor directory and set permissions
    mkdir -p /var/log/supervisor && \
    chown -R ${USER}:${USER} /var/log/supervisor

# Copy configurations
COPY docker/backend/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/backend/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Switch to non-root user
USER ${USER}

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

# Start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"] 