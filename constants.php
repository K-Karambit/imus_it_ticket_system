<?php
define('ROOT',  $_SERVER['DOCUMENT_ROOT'] . '/');

define('AZURE_APP_ID', 'abfed74c-1e74-4e39-972b-3fe0e9568db3');
define('AZURE_CLIENT_SECRET', 'jCt8Q~KDn_AvAitZekzlzK_qg472kC5EuXxnUbQ-');
define('AZURE_TENANT_ID', 'a8411b96-92c2-4538-9eba-9dcc50ef1162');
define('AZURE_GRAPH_SCOPES', 'https://graph.microsoft.com/.default');

$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
define('API', $http . $_SERVER['HTTP_HOST'] . '/api/');
define('API_KEY', 'joshbakla');

define('SITE_URL', $http . $_SERVER['HTTP_HOST'] . '/');
define('AZURE_REDIRECT_URI', $http . $_SERVER['HTTP_HOST'] . '/api/callback.php');

function defaultDatabaseConfig($default_db = 'localhost')
{
    if ($default_db == 'azure') {
        return [
            // Database connection settings
            'DB_DRIVER' => 'mysql',
            'DB_HOST' => 'srvr-sql.mysql.database.azure.com',
            'DB_NAME' => 'imus_it_ticket_system',
            'DB_USER' => 'admin1',
            'DB_PASS' => 'root-123',
            'DB_PORT' => 3306,
            'SSL_MODE' => 'REQUIRED',
            'MYSQL_ATTR_SSL_CA' => __DIR__ . '/config/DigiCertGlobalRootCA.crt.pem',
        ];
    } else {
        return [
            // Database connection settings
            'DB_DRIVER' => 'mysql',
            'DB_HOST' => 'localhost',
            'DB_NAME' => 'db_it_ticket_system',
            'DB_USER' => 'root',
            'DB_PASS' => 'password',
            'DB_PORT' => 3306,
            'SSL_MODE' => null,
            'MYSQL_ATTR_SSL_CA' => null,
        ];
    }
}

foreach (defaultDatabaseConfig('azure') as $key => $value) {
    define($key, $value);
}
