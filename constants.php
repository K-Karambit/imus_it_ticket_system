<?php

// define('ROOT',  $_SERVER['DOCUMENT_ROOT']);

// define('AZURE_APP_ID', 'abfed74c-1e74-4e39-972b-3fe0e9568db3');
// define('AZURE_CLIENT_SECRET', 'Ybq8Q~3YYVWrWzFvwPbJHZOAP.Pm3sExqxK_ta_a');
// define('AZURE_REDIRECT_URI', 'https://imus-it-ticket-system.azurewebsites.net/api/callback.php');
// define('AZURE_TENANT_ID', 'a8411b96-92c2-4538-9eba-9dcc50ef1162');
// define('AZURE_GRAPH_SCOPES', 'https://graph.microsoft.com/.default');


// function defaultApiConfig($default_api = 'azure')
// {
//     if ($default_api == 'azure') {
//         return [
//             'API' => 'https://imus-it-ticket-system.azurewebsites.net/api/',
//             'API_KEY' => 'joshbakla',
//             'SITE_URL' => 'https://imus-it-ticket-system.azurewebsites.net/',
//         ];
//     } else {
//         return [
//             'API' => 'http://localhost:8080/api/',
//             'API_KEY' => 'joshbakla',
//             'SITE_URL' => 'http://localhost:8080/',
//         ];
//     }
// }






// function defaultServer($default_server = 'local')
// {
//     if ($default_server == 'local') {
//         return [
//             'driver' => 'mysql',
//             'database' => 'db_it_ticket_system',
//             'host' => 'localhost',
//             'username' => 'root',
//             'password' => 'password',
//             'port' => '3306',
//             'charset'   => 'utf8',
//             'collation' => 'utf8_unicode_ci',
//             'prefix'    => '',
//         ];
//     } else if ($default_server == 'azure') {
//         return [
//             'driver' => 'mysql',
//             'database' => 'imus_it_ticket_system',
//             'host' => 'srvr-sql.mysql.database.azure.com',
//             'username' => 'admin1',
//             'password' => 'root-123',
//             'port' => '3306',
//             'charset'   => 'utf8',
//             'collation' => 'utf8_unicode_ci',
//             'prefix'    => '',
//             'sslmode'   => 'REQUIRED',
//             'options'   => [
//                 PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/config/DigiCertGlobalRootCA.crt.pem'
//             ],
//         ];
//     }
// }

// define('CONFIG', defaultServer('local'));

// foreach (defaultApiConfig('local') as $key => $value) {
//     define($key, $value);
// }

define('API', 'https://imus-it-ticket-system.azurewebsites.net/api/');
define('API_KEY', 'joshbakla');

define('SITE_URL', 'https://imus-it-ticket-system.azurewebsites.net/');
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

define('DB_DRIVER', 'mysql');
define('DB_NAME', 'imus_it_ticket_system');
define('DB_HOST', 'srvr-sql.mysql.database.azure.com');
define('DB_USER', 'admin1');
define('DB_PASS', 'root-123');


define('AZURE_APP_ID', 'abfed74c-1e74-4e39-972b-3fe0e9568db3');
define('AZURE_CLIENT_SECRET', 'Ybq8Q~3YYVWrWzFvwPbJHZOAP.Pm3sExqxK_ta_a');
define('AZURE_REDIRECT_URI', 'https://imus-it-ticket-system.azurewebsites.net/api/callback.php');
define('AZURE_TENANT_ID', 'a8411b96-92c2-4538-9eba-9dcc50ef1162');
define('AZURE_GRAPH_SCOPES', 'https://graph.microsoft.com/.default');

?>