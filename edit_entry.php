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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'];
    $topic = $_POST['topic'];
    $summary = $_POST['summary'];
    $entry_date = $_POST['entry_date'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("UPDATE internship_diary SET day = ?, topic = ?, summary = ?, entry_date = ?, type = ? WHERE id = ?");
    $stmt->execute([$day, $topic, $summary, $entry_date, $type, $id]);

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
    <link rel="icon" type="image/webp" href="https://files.oaiusercontent.com/file-Y41RafXVdDgqobKQVPgiqE?se=2024-12-18T20%3A25%3A53Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3D75d0ac41-ccd5-49fc-b837-4764614f6bc2.webp&sig=tZLyTOqiceXE7XfrqJMFu8nrhPL2/UQC3v%2BRSCqiKrs%3D">
    <title>Edit Entry</title>
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
        <h1>Edit Entry</h1>
        <button onclick="toggleDarkMode()" class="btn">Toggle Dark Mode</button>
    </header>
    <form method="post">
        <div class="input-field">
            <label for="day">Day:</label>
            <input type="text" id="day" name="day" value="<?= htmlspecialchars($entry['day']) ?>" required>
        </div>
        <div class="input-field">
            <label for="topic">Topic:</label>
            <input type="text" id="topic" name="topic" value="<?= htmlspecialchars($entry['topic']) ?>" required>
        </div>
        <div class="input-field">
            <label for="summary">Summary:</label>
            <textarea id="summary" name="summary" class="materialize-textarea" required><?= htmlspecialchars($entry['summary']) ?></textarea>
        </div>
        <div class="input-field">
            <label for="entry_date">Date:</label>
            <input type="date" id="entry_date" name="entry_date" value="<?= htmlspecialchars($entry['entry_date']) ?>" required>
        </div>
        <div class="input-field">
            <label for="type">Type:</label>
            <select id="type" name="type" class="browser-default" required>
                <option value="learning" <?= $entry['type'] == 'learning' ? 'selected' : '' ?>>Learning</option>
                <option value="development" <?= $entry['type'] == 'development' ? 'selected' : '' ?>>Development</option>
                <option value="holiday" <?= $entry['type'] == 'holiday' ? 'selected' : '' ?>>Holiday</option>
                <option value="reporting" <?= $entry['type'] == 'reporting' ? 'selected' : '' ?>>Reporting</option>
            </select>
        </div>
        <button type="submit" class="btn">Update Entry</button>
    </form>
</body>
</html>
