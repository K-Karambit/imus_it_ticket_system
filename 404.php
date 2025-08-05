<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! Page Not Found - Your Website Name</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for font family and animations (Tailwind doesn't handle these directly) */
        body {
            font-family: 'Poppins', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.1);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-bounce-in {
            animation: bounceIn 1s ease-out;
        }

        @keyframes rotateIn {
            from {
                transform: rotate(-90deg);
                opacity: 0;
            }

            to {
                transform: rotate(0deg);
                opacity: 1;
            }
        }

        .animate-rotate-in {
            animation: rotateIn 1s ease-out;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-5 bg-gray-100 text-gray-800 text-center leading-relaxed box-border">
    <div class="container bg-white rounded-2xl shadow-xl p-10 max-w-xl w-full animate-fade-in">
        <div class="text-green-500 text-6xl mb-5 animate-rotate-in">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="text-8xl font-bold text-blue-600 mb-0 tracking-tighter relative inline-block animate-bounce-in">404</h1>
        <h2 class="text-4xl font-semibold text-gray-800 mt-1 mb-6">Page Not Found</h2>
        <p class="text-lg mb-8">Uh oh! It looks like the page you're looking for doesn't exist or has been moved. Don't worry, it happens to the best of us!</p>
        <p class="text-lg mb-4">You can try:</p>
        <ul class="list-disc list-inside text-left mx-auto max-w-sm mb-8 space-y-2 text-gray-700">
            <li>Checking the URL for typos.</li>
            <li>Going back to the previous page.</li>
        </ul>
        <a href="?route=/home" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-full font-semibold transition-all duration-300 ease-in-out hover:bg-blue-700 hover:scale-105 hover:shadow-lg m-2">
            <i class="fas fa-home mr-2"></i> Go to Homepage
        </a>
        <!-- <a href="/contact" class="inline-block px-6 py-3 bg-transparent text-blue-600 border-2 border-blue-600 rounded-full font-semibold transition-all duration-300 ease-in-out hover:bg-blue-600 hover:text-white hover:scale-105 hover:shadow-lg m-2">
            <i class="fas fa-question-circle mr-2"></i> Contact Support
        </a> -->
        <p class="text-sm text-gray-500 mt-8">If you believe this is an error, please let us know.</p>
    </div>
</body>

</html>