<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found</title>
    <style>
        body {
            margin: 0;
            font-family: "Georgia", serif;
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: bold;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
        }

        .logo span {
            display: inline-block;
            border: 2px solid #000;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            margin-right: 0.5rem;
        }

        h1 {
            font-size: 6rem;
            margin: 0;
            color: #343a40;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }

        p {
            max-width: 600px;
            margin-bottom: 2rem;
        }

        a.button {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            background-color: #005ea2;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        a.button:hover {
            background-color: #004080;
        }

        .footer {
            position: absolute;
            bottom: 1rem;
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div class="logo">
        <img src="<?= $helper->public_url('assets/img/it_logo.png') ?>" alt="CITRMU LOGO" height="150" width="150">
    </div>

    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
    <a href="/" class="button">Return to Home</a>

    <div class="footer">
        CITRMU | All rights reserved
    </div>

</body>

</html>