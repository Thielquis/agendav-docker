#!/bin/bash
set -euo pipefail

: "${AGENDAV_CALDAV_SERVER:?AGENDAV_CALDAV_SERVER is required}"
: "${AGENDAV_CSRF_SECRET:?AGENDAV_CSRF_SECRET is required}"
: "${AGENDAV_FOOTER:?AGENDAV_FOOTER is required}"
: "${AGENDAV_TITLE:?AGENDAV_TITLE is required}"

printf 'date.timezone = "%s"\n' "${AGENDAV_TIMEZONE:-UTC}" \
    > "${PHP_INI_DIR}/conf.d/agendav-timezone.ini"

touch /var/agendav/db.sqlite
chmod 640 /var/agendav/db.sqlite

cd /var/www/agendav
php bin/agendavcli migrations:migrate --no-interaction

if [ "x$1" = 'xapache2' ]; then
	echo "Start webserver"
	exec apache2-foreground
else
	exec "$@"
fi
