<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

require 'database.php';

$stmt = $pdo->query("SELECT * FROM internship_diary ORDER BY entry_date ASC");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <title>Internship Diary Calendar</title>
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
        #calendar {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        body.dark-mode #calendar {
            background-color: #333;
            color: #ffffff;
        }
        .fc-header-toolbar {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .fc-day-header {
            background-color: #f4f4f9;
            color: #333;
            padding: 10px 0;
        }
        body.dark-mode .fc-day-header {
            background-color: #121212;
            color: #ffffff;
        }
        .fc-day-grid-event {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px;
            white-space: normal; /* Allow text to wrap */
        }
        .fc-event-container a {
            display: block; /* Make the event container span the entire box */
            height: auto; /* Adjust the height of the container */
            min-height: 80px; /* Set a minimum height */
        }
        .fc-title {
            white-space: normal; 
            display: block; /* Make the event container span the entire box */
            height: auto; /* Adjust the height of the container */
            max-height: 80px;/* Ensure the title wraps within the container */
        }
        .fc-event-type-learning {
            background-color: #4CAF50; /* Green */
        }
        .fc-event-type-development {
            background-color: #FF9800; /* Orange */
        }
        .fc-event-type-reporting {
            background-color: #2196F3; /* Blue */
        }
        .fc-event-type-holiday {
            background-color: #F44336; /* Red */
        }
        body.dark-mode .fc-event-type-learning {
            background-color: #2E7D32; /* Dark Green */
        }
        body.dark-mode .fc-event-type-development {
            background-color: #EF6C00; /* Dark Orange */
        }
        body.dark-mode .fc-event-type-reporting {
            background-color: #1565C0; /* Dark Blue */
        }
        body.dark-mode .fc-event-type-holiday {
            background-color: #C62828; /* Dark Red */
        }
        @media (max-width: 768px) {
            header {
                padding: 5px 0;
            }
            .add-entry-btn {
                padding: 5px 10px;
                margin: 5px 0;
            }
            #calendar {
                margin: 20px auto;
                padding: 0 5px;
            }
            .fc-header-toolbar {
                padding: 5px;
            }
            .fc-day-header {
                padding: 5px 0;
            }
            .fc-event-container a {
                min-height: 60px; /* Adjust the minimum height for smaller screens */
            }
            .fc-title {
                max-height: 60px; /* Ensure the title wraps within the container for smaller screens */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Internship Diary Calendar</h1>
        <a href="index.php" class="add-entry-btn">Back to Diary</a>
        <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
    </header>
    <div id="calendar"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }

        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: [
                    <?php foreach ($entries as $entry): ?>
                    {
                        title: '<?= htmlspecialchars($entry['topic']) ?>',
                        start: '<?= htmlspecialchars($entry['entry_date']) ?>',
                        url: 'view_entry.php?id=<?= $entry['id'] ?>',
                        className: 'fc-event-type-<?= htmlspecialchars($entry['type']) ?>'
                    },
                    <?php endforeach; ?>
                ],
                eventRender: function(event, element) {
                    element.find('.fc-title').html(event.title);
                }
            });
        });
    </script>
</body>
</html>
