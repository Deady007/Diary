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

// Database connection (assuming you have the database setup)
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for adding a new entry
    $day = $_POST['day'];
    $topic = $_POST['topic'];
    $summary = $_POST['summary'];
    $entry_date = $_POST['entry_date'];

    // Insert new entry into the database
    $stmt = $pdo->prepare("INSERT INTO internship_diary (day, topic, summary, entry_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$day, $topic, $summary, $entry_date]);

    // Redirect back to the main page
    header('Location: index.php');
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
    <title>Add New Entry - Internship Diary</title>
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
            color: #ffffff;
        }
        @media (max-width: 768px) {
            header {
                padding: 5px 0;
            }
            .widget {
                padding: 5px;
                margin: 5px;
            }
            .input-field {
                margin: 5px 0;
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
        <h1>Add New Entry</h1>
        <button onclick="toggleDarkMode()" class="btn">Toggle Dark Mode</button>
    </header>
    
    <div class="container">
        <form method="post" action="">
            <div class="input-field">
                <label for="day">Day:</label>
                <input type="text" id="day" name="day" required>
            </div>
            
            <div class="input-field">
                <label for="topic">Topic:</label>
                <input type="text" id="topic" name="topic" required>
            </div>
            
            <div class="input-field">
                <label for="summary">Summary:</label>
                <textarea id="summary" name="summary" class="materialize-textarea" required></textarea>
            </div>
            
            <div class="input-field">
                <label for="entry_date">Entry Date:</label>
                <input type="date" id="entry_date" name="entry_date" required>
            </div>

            <div class="input-field">
                <label for="type">Type:</label>
                <select id="type" name="type" class="browser-default" required>
                    <option value="learning">Learning</option>
                    <option value="development">Development</option>
                    <option value="holiday">Holiday</option>
                    <option value="reporting">Reporting</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Save Entry</button>
        </form>
    </div>

    <a href="index.php" class="btn">Back to Diary</a>
</body>
</html>
