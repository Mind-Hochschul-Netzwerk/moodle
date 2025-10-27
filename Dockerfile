FROM trafex/php-nginx:3.5.0

LABEL Maintainer="Henrik Gebauer <code@henrik-gebauer.de>" \
      Description="mind-hochschul-netzwerk.de"

EXPOSE 80
HEALTHCHECK --interval=10s CMD curl --silent --fail http://127.0.0.1/fpm-ping

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER root

# apply (security) updates
RUN --mount=type=cache,target=/var/cache/apk set -x \
  && apk upgrade

# workaround for iconv issue
RUN --mount=type=cache,target=/var/cache/apk set -x \
  && apk add --repository http://dl-cdn.alpinelinux.org/alpine/v3.13/community/ gnu-libiconv==1.15-r3
ENV LD_PRELOAD="/usr/lib/preloadable_libiconv.so php"

RUN --mount=type=cache,target=/var/cache/apk set -x \
  && apk add \
      php83-ldap \
      php83-zip \
      php83-iconv \
      php83-simplexml \
      php83-soap \
      php83-sodium \
      php83-exif \
      php83-tidy

COPY --chown=nobody moodle-loop.sh /
COPY --chown=nobody docker/build-cache dependencies.* /tmp/build/

RUN <<EOT
  set -ex
  php /tmp/build/dependencies.php --install
  rm -rf /tmp/build
  cd /var/www/
  composer install --no-dev --classmap-authoritative
  # use common characters in mail addressess, see https://tracker.moodle.org/browse/MDL-71652
  sed "s#\$subaddress = base64_encode(implode(\$data));#\$subaddress = str_replace('=', '', strtr(base64_encode(implode(\$data)), '+/', '-.'));#" -i /var/www/public/lib/classes/message/inbound/address_manager.php
  sed "s#\$data = base64_decode(\$encodeddata, true);#\$data = base64_decode(strtr(\$encodeddata, '-.', '+/'), true);#" -i /var/www/public/lib/classes/message/inbound/address_manager.php
  # fix images in front page wiki, see https://tracker.moodle.org/browse/MDL-81879
  sed "s#require_login#\/\/require_login#" -i /var/www/public/mod/wiki/lib.php
  # set up moodle-loop.sh to run the moodle cron script
  chmod u+x /moodle-loop.sh
  echo -e "[program:moodle-loop]\ncommand=/moodle-loop.sh\nautorestart=true" >> /etc/supervisor/conf.d/supervisord.conf
  true
EOT

USER nobody

COPY server/nginx/ /etc/nginx/
COPY server/php-custom.ini /etc/php83/conf.d/custom.ini
COPY src/config.php /var/www/config.php
COPY src/akademieprogramm.php /var/www/public/
