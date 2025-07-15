<?php

define('ROOT',  $_SERVER['DOCUMENT_ROOT']);

define('AZURE_APP_ID', 'abfed74c-1e74-4e39-972b-3fe0e9568db3');
define('AZURE_CLIENT_SECRET', 'jCt8Q~KDn_AvAitZekzlzK_qg472kC5EuXxnUbQ-');
define('AZURE_REDIRECT_URI', 'https://imus-it-ticket-system.azurewebsites.net/api/callback.php');
define('AZURE_TENANT_ID', 'a8411b96-92c2-4538-9eba-9dcc50ef1162');
define('AZURE_GRAPH_SCOPES', 'https://graph.microsoft.com/.default');










function defaultApiConfig($default_api = 'azure')
{
    if ($default_api == 'azure') {
        return [
            'API' => 'https://imus-it-ticket-system.azurewebsites.net/api/',
            'API_KEY' => 'joshbakla',
            'SITE_URL' => 'https://imus-it-ticket-system.azurewebsites.net/',
        ];
    } else {
        return [
            'API' => 'http://localhost:8080/api/',
            'API_KEY' => 'joshbakla',
            'SITE_URL' => 'http://localhost:8080/',
        ];
    }
}






function defaultServer($default_server = 'local')
{
    if ($default_server == 'local') {
        return [
            'driver' => 'mysql',
            'database' => 'db_it_ticket_system',
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'password',
            'port' => '3306',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
    } else if ($default_server == 'azure') {
        return [
            'driver' => 'mysql',
            'database' => 'imus_it_ticket_system',
            'host' => 'srvr-sql.mysql.database.azure.com',
            'username' => 'admin1',
            'password' => 'root-123',
            'port' => '3306',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'sslmode'   => 'REQUIRED',
            'options'   => [
                PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/config/DigiCertGlobalRootCA.crt.pem'
            ],
        ];
    }
}

define('CONFIG', defaultServer('azure'));

foreach (defaultApiConfig() as $key => $value) {
    define($key, $value);
}
