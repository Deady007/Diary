<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

require 'database.php';

$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM internship_diary WHERE topic LIKE ? OR summary LIKE ? OR entry_date LIKE ? OR type LIKE ? ORDER BY CAST(day AS UNSIGNED) ASC");
    $stmt->execute(['%' . $searchQuery . '%', '%' . $searchQuery . '%', '%' . $searchQuery . '%', '%' . $searchQuery . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM internship_diary ORDER BY CAST(day AS UNSIGNED) ASC");
}

$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="https://files.oaiusercontent.com/file-Y41RafXVdDgqobKQVPgiqE?se=2024-12-18T20%3A25%3A53Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3D75d0ac41-ccd5-49fc-b837-4764614f6bc2.webp&sig=tZLyTOqiceXE7XfrqJMFu8nrhPL2/UQC3v%2BRSCqiKrs%3D">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Internship Diary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            transition: background-color 0.5s;
        }
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .add-entry-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            display: inline-block;
        }
        .task-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            background-color: #fff;
            transition: transform 0.3s, background-color 0.5s;
        }
        .task-card:hover {
            transform: scale(1.05);
        }
        .task-card .actions {
            margin-top: 10px;
        }
        .task-card .actions a {
            margin-right: 10px;
        }
        .task-card.learning {
            background-color: #e0f7fa;
        }
        .task-card.development {
            background-color: #ffe0b2;
        }
        .task-card.holiday {
            background-color: #f8bbd0;
        }
        .task-card.reporting {
            background-color: #d1c4e9;
        }
        body.dark-mode .task-card {
            background-color: #333;
            color: #ffffff;
        }
        body.dark-mode .task-card.learning {
            background-color: #004d40;
        }
        body.dark-mode .task-card.development {
            background-color: #ff6f00;
        }
        body.dark-mode .task-card.holiday {
            background-color: #880e4f;
        }
        body.dark-mode .task-card.reporting {
            background-color: #4a148c;
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
            .add-entry-btn {
                padding: 5px 10px;
                margin: 5px 0;
            }
            .task-card {
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
        <h1>Internship Diary</h1>
        <a href="add_entry.php" class="add-entry-btn">Add New Entry</a>
        <a href="calendar.php" class="add-entry-btn">View Calendar</a> <!-- New link to calendar page -->
        <button onclick="toggleDarkMode()" class="add-entry-btn">Toggle Dark Mode</button>
    </header>
    
    <div class="container">
    <form method="get" action="index.php" style="display: inline-block;">

    <input type="text" name="search" placeholder="Search by topic, summary, date, or type" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit" class="btn">Search</button>
        </form> 
        <?php foreach ($entries as $entry): ?>
            <?php
                $entryClass = '';
                if ($entry['type'] == 'learning') {
                    $entryClass = 'learning';
                } elseif ($entry['type'] == 'development') {
                    $entryClass = 'development';
                } elseif ($entry['type'] == 'holiday') {
                    $entryClass = 'holiday';
                } elseif ($entry['type'] == 'reporting') {
                    $entryClass = 'reporting';
                }
            ?>
            <div class="task-card <?= $entryClass ?>">
                <h2>Day: <?= htmlspecialchars($entry['day']) ?></h2>
                <p><strong>Topic:</strong> <?= htmlspecialchars($entry['topic']) ?></p>
                <p><strong>Summary:</strong> <?= htmlspecialchars(substr($entry['summary'], 0, 50)) ?>...</p>
                <p><strong>Date:</strong> <?= htmlspecialchars($entry['entry_date']) ?></p>
                <div class="actions">
                    <a href="view_entry.php?id=<?= $entry['id'] ?>" class="btn">View</a>
                    <a href="edit_entry.php?id=<?= $entry['id'] ?>" class="btn">Edit</a>
                    <a href="delete_entry.php?id=<?= $entry['id'] ?>" onclick="return confirm('Are you sure?')" class="btn red">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
