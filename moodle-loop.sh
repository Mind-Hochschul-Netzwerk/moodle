#!/bin/sh

while true; do
  /usr/bin/php /var/www/admin/cli/cron.php --keep-alive=50 >/dev/null
  sleep 10
done
