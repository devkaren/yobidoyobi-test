#!/bin/sh

php /var/www/artisan app:ci -p

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
