<?php

include __DIR__ . '/../config/config.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use Models\User;
use Models\Activity;
use Models\Session;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$clientId = $_ENV['AZURE_APP_ID'];
$clientSecret = $_ENV['AZURE_CLIENT_SECRET'];
$redirectUri = $_ENV['AZURE_REDIRECT_URI'];
$tenantId = $_ENV['AZURE_TENANT_ID'];
$scopes = explode(' ', $_ENV['AZURE_GRAPH_SCOPES']);

// Create the OAuth 2.0 provider
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => $clientId,
    'clientSecret'            => $clientSecret,
    'redirectUri'             => $redirectUri,
    'urlAuthorize'            => "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/authorize",
    'urlAccessToken'          => "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
    'urlResourceOwnerDetails' => "https://graph.microsoft.com/v1.0/me", // <-- This is the UserInfo endpoint
    'scopes'                  => implode(' ', $scopes)
]);

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    $errorDescription = htmlspecialchars($_GET['error_description'] ?? 'No description provided.');
    exit("Authentication Error: {$error} - {$errorDescription}");
}

if (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']); // Clear the state
    }
    exit('Invalid state parameter. Possible Cross-Site Request Forgery (CSRF) attack.');
}

if (isset($_GET['code'])) {
    try {
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $_SESSION['accessToken'] = $accessToken->getToken();
        $_SESSION['refreshToken'] = $accessToken->getRefreshToken();
        $_SESSION['expires'] = $accessToken->getExpires();

        $resourceOwner = $provider->getResourceOwner($accessToken);
        $userData = $resourceOwner->toArray();

        $user = User::where('email', $userData['userPrincipalName'] ?? null)->where('is_deleted', 0)->first();

        if (!$user) {
            header('Location: ../index.php?microsoftErrorMessage=Invalid username or password.');
            exit();
        }

        $generated_token = md5(random_bytes(10));
        $session = new Session();
        $session->user_id = $user->user_id;
        $session->token = $generated_token;

        $datetime = date('m/d/Y h:i A');
        Activity::addActivityLog('login', "logged in $datetime", $user->user_id);

        $session->save();

        $_SESSION['SESSION_TOKEN'] = $generated_token;
        $_SESSION['SESSION_KEY'] = base64_encode(random_bytes(1000));

        header('Location: ../index.php?route=/home');
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit('Error getting access token: ' . $e->getMessage());
    }
} else {
    exit('Authentication failed: No authorization code received.');
}
