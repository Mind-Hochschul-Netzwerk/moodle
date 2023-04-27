FROM trafex/php-nginx:3.0.0

LABEL Maintainer="Henrik Gebauer <code@henrik-gebauer.de>" \
      Description="mind-hochschul-netzwerk.de"

COPY --chown=nobody assets/ get-resources.sh resources.list /tmp/build/

USER root

RUN set -ex \
  && apk --no-cache add \
    php81-ldap \
    php81-zip \
    php81-iconv \
    php81-simplexml \
    php81-tokenizer \
    php81-soap \
    php81-fileinfo \
    php81-sodium \
    php81-exif \
  && /tmp/build/get-resources.sh \
  && tar --strip-components=1 -C /var/www/html -xzf /tmp/build/moodle-*.tgz moodle \
  && for f in /tmp/build/mod_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/mod -qq; fi; done \
  && for f in /tmp/build/repository_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/repository -qq; fi; done \
  && for f in /tmp/build/theme_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/theme -qq; fi; done \
  && for f in /tmp/build/enrol_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/enrol -qq; fi; done \
  && for f in /tmp/build/filter_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/filter -qq; fi; done \
  && for f in /tmp/build/local_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/local -qq; fi; done \
  && for f in /tmp/build/availability_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/availability/condition -qq; fi; done \
  && unzip /tmp/build/mathjax.zip -d /var/www/html/ -qq && mv /var/www/html/MathJax-* /var/www/html/mathjax \
  && rm -rf /tmp/build \
  # use common characters in mail addressess, see https://tracker.moodle.org/browse/MDL-71652
  && sed "s#\$subaddress = base64_encode(implode(\$data));#\$subaddress = str_replace('=', '', strtr(base64_encode(implode(\$data)), '+/', '-.'));#" -i /var/www/html/lib/classes/message/inbound/address_manager.php \
  && sed "s#\$data = base64_decode(\$encodeddata, true);#\$data = base64_decode(strtr(\$encodeddata, '-.', '+/'), true);#" -i /var/www/html/lib/classes/message/inbound/address_manager.php \
  && true

COPY server/nginx.conf /etc/nginx/nginx.conf
COPY server/server-default.conf /etc/nginx/conf.d/default.conf
COPY server/php-custom.ini /etc/php81/conf.d/custom.ini
COPY server/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY server/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY server/crontab /etc/crontabs/nobody
COPY server/entrypoint.sh /entrypoint.sh

COPY src/config.php /var/www/html/config.php

CMD "/entrypoint.sh"