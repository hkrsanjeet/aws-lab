<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Intentionally vulnerable SSRF implementation for lab purposes
if (isset($_GET['search'])) {
    $url = $_GET['search'];
    
    // Minimal validation (intentionally weak for demonstration)
    if (strpos($url, 'http') === 0) {
        // Fetch the content (vulnerable implementation)
        $response = @file_get_contents($url);
        
        if ($response === false) {
            echo "<div class='error'>Failed to fetch URL: " . htmlspecialchars($url) . "</div>";
        } else {
            echo "<div class='result'>";
            echo "<h3>Content from: " . htmlspecialchars($url) . "</h3>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
            echo "</div>";
        }
    } else {
        echo "<div class='error'>URL must start with http</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SSRF Vulnerable SERVER</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .search-box {
            margin: 20px 0;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .result, .error {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        .result {
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
        }
        .error {
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .warning {
            background-color: #fff3e0;
            border: 1px solid #ffcc80;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SSRF Vulnerability Lab</h1>
        
        <div class="warning">
            <strong>Warning:</strong> This is a vulnerable application for educational purposes only.
            Do not deploy this in production environments.
        </div>
        
        <div class="search-box">
            <form method="GET">
                <label for="search"><strong>Enter URL to fetch:</strong></label><br>
                <input type="text" id="search" name="search" 
                       placeholder="http://example.com" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <input type="submit" value="Fetch">
            </form>
        </div>
        
        <?php if (isset($_GET['search'])): ?>
            <div style="margin-top: 10px;">
                <a href="?" style="color: blue; text-decoration: none;">← Clear results</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
