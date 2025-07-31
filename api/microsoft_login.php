<?php
require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../constants.php';

// Load environment variables
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

$clientId = AZURE_APP_ID; // Use the constant defined in constants.php
$clientSecret = AZURE_CLIENT_SECRET; // Use the constant defined in constants.php
$redirectUri =  AZURE_REDIRECT_URI; // Use the constant defined in constants.php
$tenantId =  AZURE_TENANT_ID; // Use the constant defined in constants.php
$scopes = explode(' ', AZURE_GRAPH_SCOPES); // Convert space-separated string to array

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
