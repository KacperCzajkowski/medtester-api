FROM med-tester-api-base as med-tester-api-dev

COPY vhost.conf /etc/apache2/sites-available/med-tester-api.conf
RUN a2ensite med-tester-api

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"