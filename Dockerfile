FROM trafex/php-nginx:3.4.0

LABEL Maintainer="Henrik Gebauer <code@henrik-gebauer.de>" \
      Description="mind-hochschul-netzwerk.de"

EXPOSE 80
HEALTHCHECK --interval=10s CMD curl --silent --fail http://127.0.0.1/fpm-ping

COPY --chown=nobody assets/ get-resources.sh resources.list /tmp/build/
COPY --chown=nobody moodle-loop.sh /

USER root

# workaround for iconv issue
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/v3.13/community/ gnu-libiconv==1.15-r3
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN set -ex \
  && apk --no-cache add \
    php82-ldap \
    php82-zip \
    php82-iconv \
    php82-simplexml \
    php82-soap \
    php82-sodium \
    php82-exif \
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
  # set up moodle-loop.sh to run the moodle cron script
  && chmod u+x /moodle-loop.sh \
  && echo -e "[program:moodle-loop]\ncommand=/moodle-loop.sh\nautorestart=true" >> /etc/supervisor/conf.d/supervisord.conf \
  && true

USER nobody

COPY server/nginx/ /etc/nginx/
COPY server/php-custom.ini /etc/php81/conf.d/custom.ini
COPY src/config.php /var/www/html/config.php
