<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirect to login page if not authenticated
    header('Location: login.php');
    exit;
}

require 'database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM internship_diary WHERE id = ?");
$stmt->execute([$id]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$entry) {
    echo "Entry not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>View Entry</title>
    <style>
        body {
            transition: background-color 0.5s;
        }
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .widget {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            background-color: #fff;
            transition: transform 0.3s, background-color 0.5s;
        }
        .widget:hover {
            transform: scale(1.05);
        }
        body.dark-mode .widget {
            background-color: #333;
            color:rgb(255, 255, 255);
        }
        @media (max-width: 768px) {
            header {
                padding: 5px 0;
            }
            .widget {
                padding: 5px;
                margin: 5px;
            }
            .container {
                padding: 0 10px;
            }
        }
    </style>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
        window.onload = function() {
            const hour = new Date().getHours();
            if (hour >= 18 || hour < 6) {
                document.body.classList.add('dark-mode');
            }
        }
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/Diary/service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        let deferredPrompt;
        const installButton = document.createElement('button');
        installButton.textContent = 'Install App';
        installButton.style.display = 'none';
        installButton.className = 'btn'; // Add a class for styling
        document.addEventListener('DOMContentLoaded', () => {
            document.body.appendChild(installButton);
        });

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installButton.style.display = 'block';

            installButton.addEventListener('click', () => {
                installButton.style.display = 'none';
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    </script>
    <link rel="manifest" href="/Diary/manifest.json">
</head>
<body>
    <header>
        <h1>View Entry</h1>
        <button onclick="toggleDarkMode()" class="btn">Toggle Dark Mode</button>
    </header>
    <div class="container">
        <div class="widget">
            <h2>Day: <?= htmlspecialchars($entry['day']) ?></h2>
            <p><strong>Topic:</strong> <?= htmlspecialchars($entry['topic']) ?></p>
            <p><strong>Summary:</strong> <?= htmlspecialchars($entry['summary']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($entry['entry_date']) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars($entry['type']) ?></p>
        </div>
        <a href="index.php" class="btn">Back to Diary</a>
    </div>
</body>
</html>
