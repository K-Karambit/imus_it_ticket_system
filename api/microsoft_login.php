<?php
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

$clientId = getenv['AZURE_APP_ID'];
$clientSecret = getenv['AZURE_CLIENT_SECRET'];
$redirectUri = getenv['AZURE_REDIRECT_URI'];
$tenantId = getenv['AZURE_TENANT_ID'];
$scopes = explode(' ', getenv['AZURE_GRAPH_SCOPES']); // Convert space-separated string to array

// Create the OAuth provider
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => $clientId,
    'clientSecret'            => $clientSecret,
    'redirectUri'             => $redirectUri,
    'urlAuthorize'            => "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/authorize",
    'urlAccessToken'          => "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
    'urlResourceOwnerDetails' => '', // Not directly used for Graph, handled by SDK
    'scopes'                  => implode(' ', $scopes)
]);

// Generate the authorization URL and redirect the user
$authorizationUrl = $provider->getAuthorizationUrl();
$_SESSION['oauth2state'] = $provider->getState(); // Store state for verification

header('Location: ' . $authorizationUrl);
exit();
