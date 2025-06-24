<?php


function getCurrentUrl()
{
    // Check if the request is over HTTPS
    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

    // Get the protocol (http or https)
    $protocol = $isHttps ? 'https' : 'http';

    // Get the domain name (hostname)
    $host = $_SERVER['HTTP_HOST'];

    // Get the requested URI (path and query string)
    $uri = $_SERVER['REQUEST_URI'];

    // Return the full URL
    return $protocol . '://' . $host . $uri;
}

echo getCurrentUrl();
