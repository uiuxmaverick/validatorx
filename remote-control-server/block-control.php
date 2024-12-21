<?php
session_start(); // Start the session

// Define the file to store blocked websites (a simple JSON database).
$database_file = 'blocked-sites.json';

// Load existing blocked sites.
$blocked_sites = file_exists($database_file) ? json_decode(file_get_contents($database_file), true) : [];

// Handle form submission to block/unblock sites.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $site_url = trim($_POST['site_url'] ?? '');

    if ($action && $site_url) {
        if ($action === 'block') {
            $blocked_sites[$site_url] = [
                'blocked' => true,
                'message' => $_POST['message'] ?? 'This site has been blocked by the developer.',
            ];
        } elseif ($action === 'unblock') {
            unset($blocked_sites[$site_url]);
        }

        // Save changes to the JSON file.
        file_put_contents($database_file, json_encode($blocked_sites));
    }
}

// Output the blocked sites JSON if requested (used by the plugin).
if (isset($_GET['api']) && $_GET['api'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode($blocked_sites);
    exit;
}

// Password protection
$provided_password = $_POST['password'] ?? '';
$correct_password = 'SET_YOUR_ADMIN_PASSWORD'; // Set your password here
$authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'];

if (!$authenticated) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $provided_password === $correct_password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = time(); // Record the login time
        $authenticated = true;
    } else {
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Login</title>';
        echo '<style>'; 
        echo 'body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }';
        echo 'form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 300px; text-align: center; }';
        echo 'h1 { margin-bottom: 20px; font-size: 24px; color: #333; }';
        echo 'input[type="password"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }';
        echo 'button { width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }';
        echo 'button:hover { background-color: #0056b3; }';
        echo '</style></head>'; 
        echo '<body><form method="POST">';
        echo '<h1>ValidatorX Login</h1>';
        echo '<input type="password" name="password" placeholder="Enter Password" required>'; 
        echo '<button type="submit">Login</button>'; 
        echo '</form></body></html>';
        exit;
    }
}

// Check if the session has expired (1-hour limit)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 3600)) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Session Expired</title>';
    echo '<style>'; 
    echo 'body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }';
    echo 'div { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 300px; text-align: center; }';
    echo 'h1 { margin-bottom: 20px; font-size: 24px; color: #333; }';
    echo 'a { color: #007BFF; text-decoration: none; font-weight: bold; }';
    echo 'a:hover { text-decoration: underline; }';
    echo '</style></head>'; 
    echo '<body><div>';
    echo '<h1>Session Expired</h1>';
    echo '<p>Your session has expired. Please <a href="">log in again</a>.</p>';
    echo '</div></body></html>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ValidatorX Control Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #007BFF;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button.unblock {
            background-color: #dc3545;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        form.inline {
            display: inline;
        }
    </style>
</head>
<body>

<h1>ValidatorX Control Panel</h1>

<!-- Form to block/unblock a site -->
<form method="POST">
    <div class="form-group">
        <label for="site_url">Site URL:</label>
        <input type="url" name="site_url" id="site_url" required>
    </div>
    <div class="form-group">
        <label for="message">Block Message:</label>
        <textarea name="message" id="message" placeholder="Enter the block message..."></textarea>
    </div>
    <div class="form-group">
        <button type="submit" name="action" value="block">Block Site</button>
    </div>
</form>

<h2>Blocked Sites</h2>

<!-- Display a list of blocked sites -->
<table>
    <thead>
        <tr>
            <th>Site URL</th>
            <th>Message</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($blocked_sites as $site => $data): ?>
        <tr>
            <td><?php echo htmlspecialchars($site); ?></td>
            <td><?php echo htmlspecialchars($data['message']); ?></td>
            <td>
                <!-- Unblock form -->
                <form method="POST" class="inline">
                    <input type="hidden" name="site_url" value="<?php echo htmlspecialchars($site); ?>">
                    <button type="submit" name="action" value="unblock" class="unblock">Unblock</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
