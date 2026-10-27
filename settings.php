<?php

$requiredEnv = static function (string $name): string {
    $value = getenv($name);

    if ($value === false || $value === '') {
        throw new RuntimeException("Missing required environment variable: {$name}");
    }

    return $value;
};

$caldavServer = $requiredEnv('AGENDAV_CALDAV_SERVER');
$publicCalDavUrl = getenv('AGENDAV_CALDAV_PUBLIC_URL');

return [
    'site.title' => $requiredEnv('AGENDAV_TITLE'),
    'site.footer' => $requiredEnv('AGENDAV_FOOTER'),
    'db.options' => [
        'path' => '/var/agendav/db.sqlite',
        'driver' => 'pdo_sqlite',
    ],
    'csrf.secret' => $requiredEnv('AGENDAV_CSRF_SECRET'),
    'session.handler' => 'native',
    'log.path' => getenv('AGENDAV_LOG_DIR') ?: '/tmp/',
    'caldav.baseurl' => $caldavServer,
    'caldav.baseurl.public' => $publicCalDavUrl ?: $caldavServer,
    'defaults.timezone' => getenv('AGENDAV_TIMEZONE') ?: 'UTC',
    'defaults.language' => getenv('AGENDAV_LANG') ?: 'en',
    'defaults.time_format' => '24',
    'defaults.date_format' => 'ymd',
    'defaults.weekstart' => (int) (getenv('AGENDAV_WEEKSTART') ?: 1),
    'logout.redirection' => '',
    'calendar.sharing' => true,
];
