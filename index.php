<?php
// Create or open the SQLite database
$db = new SQLite3('notepad.db');

// Create a table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS notes (id INTEGER PRIMARY KEY, content TEXT)");

// Handle saving text
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['note'] ?? '';
    $db->exec("DELETE FROM notes"); // Keep only the latest note
    $stmt = $db->prepare("INSERT INTO notes (content) VALUES (:content)");
    $stmt->bindValue(':content', $text, SQLITE3_TEXT);
    $stmt->execute();
}

// Retrieve saved text
$result = $db->query("SELECT content FROM notes LIMIT 1");
$savedText = $result->fetchArray(SQLITE3_ASSOC)['content'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nahid's Notepad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 50%;
            text-align: center;
        }
        textarea {
            width: 100%;
            height: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Virtual Notepad</h2>
        <form method="post">
            <textarea name="note"><?php echo htmlspecialchars($savedText); ?></textarea>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
