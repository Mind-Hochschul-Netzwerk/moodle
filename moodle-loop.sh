#!/bin/sh

while true; do
  /usr/bin/php /var/www/html/admin/cli/cron.php --keep-alive=50 >/dev/null
  sleep 10
done
