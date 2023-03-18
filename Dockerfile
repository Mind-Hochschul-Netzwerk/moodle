FROM mindhochschulnetzwerk/php-base

LABEL Maintainer="Henrik Gebauer <code@henrik-gebauer.de>" \
      Description="mind-hochschul-netzwerk.de"

RUN set -ex \
  && apk --no-cache add \
    php7-mysqli \
    php7-iconv \
    php7-zip \
    php7-simplexml \
    php7-fileinfo \
    php7-tokenizer \
    php7-xmlrpc \
    php7-soap \
    php7-curl \
    php7-ctype \
    php7-gd \
    php7-dom \
    php7-xmlreader \
    php7-json \
    php7-intl \
    php7-mbstring \
    php7-session \
    php7-ldap \
    php7-sodium \
    php7-exif \
    ghostscript \
    poppler-utils

COPY assets/ get-resources.sh resources.list /tmp/build/

RUN set -ex \
  && /tmp/build/get-resources.sh \
  && tar --strip-components=1 -C /var/www/html -xzf /tmp/build/moodle-*.tgz moodle \
  && for f in /tmp/build/mod_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/mod -qq; fi; done \
  && for f in /tmp/build/repository_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/repository -qq; fi; done \
  && for f in /tmp/build/atto_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/lib/editor/atto/plugins -qq; fi; done \
  && for f in /tmp/build/theme_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/theme -qq; fi; done \
  && for f in /tmp/build/enrol_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/enrol -qq; fi; done \
  && for f in /tmp/build/filter_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/filter -qq; fi; done \
  && for f in /tmp/build/local_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/local -qq; fi; done \
  && for f in /tmp/build/availability_*.zip; do if [ -e "$f" ]; then unzip "$f" -d /var/www/html/availability/condition -qq; fi; done \
  && unzip /tmp/build/mathjax.zip -d /var/www/html/ -qq && mv /var/www/html/MathJax-* /var/www/html/mathjax \
  # use common characters in mail addressess, see https://tracker.moodle.org/browse/MDL-71652
  && sed "s#\$subaddress = base64_encode(implode(\$data));#\$subaddress = str_replace('=', '', strtr(base64_encode(implode(\$data)), '+/', '-.'));#" -i /var/www/html/lib/classes/message/inbound/address_manager.php \
  && sed "s#\$data = base64_decode(\$encodeddata, true);#\$data = base64_decode(strtr(\$encodeddata, '-.', '+/'), true);#" -i /var/www/html/lib/classes/message/inbound/address_manager.php \
  && mkdir /moodledata \
  && chown -R nobody:nobody /var/www/html \
  && chown -R www-data:www-data /moodledata \
  && echo "* * * * * /usr/bin/php /var/www/html/admin/cli/cron.php >/dev/null" > /etc/crontabs/www-data \
  && rm -rf /tmp/build \
  && echo "client_max_body_size 100m;" > /etc/nginx/conf.d/server-client_max_body_size \
  && echo "max_input_vars = 5000" >> /etc/php7/conf.d/custom.ini

COPY entry.d/ /entry.d
COPY src/ /var/www/html/
