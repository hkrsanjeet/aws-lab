<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_metadata($path) {
    $token_url = '<http://169.254.169.254/latest/api/token>';
    $metadata_url = '<http://169.254.169.254/latest/meta-data/>' . $path;

    try {
        // Get IMDSv2 token
        $token = file_get_contents(
            $token_url,
            false,
            stream_context_create([
                'http' => [
                    'method' => 'PUT',
                    'header' => "X-aws-ec2-metadata-token-ttl-seconds: 21600\\r\\n"
                ]
            ])
        );

        // Get metadata with token
        return file_get_contents(
            $metadata_url,
            false,
            stream_context_create([
                'http' => [
                    'header' => "X-aws-ec2-metadata-token: $token\\r\\n"
                ]
            ])
        );
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

if (isset($_GET['path'])) {
    $result = get_metadata($_GET['path']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AWS SSRF Lab</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .warning { background: #fff3e0; border-left: 4px solid #ff9800; padding: 10px; margin-bottom: 20px; }
        .search-box { margin: 20px 0; }
        input[type="text"] { width: 300px; padding: 8px; }
        input[type="submit"] { padding: 8px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>AWS Metadata SSRF Lab</h1>

        <div class="warning">
            <strong>⚠️ Warning:</strong> This lab demonstrates security vulnerabilities.
            Only use this in controlled environments with proper authorization.
        </div>

        <div class="search-box">
            <form method="GET">
                <label><strong>Metadata Path:</strong></label><br>
                <input type="text" name="path" value="iam/security-credentials/" placeholder="iam/security-credentials/">
                <input type="submit" value="Fetch">
            </form>
        </div>

        <?php if (isset($result)): ?>
            <h3>Results:</h3>
            <pre><?php echo htmlspecialchars($result); ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>
