<?php
/**
 * Generate Signed Feedback Link
 * Similar to the Core PHP API provided
 * 
 * Usage: Access this file via browser or POST request
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\SignedUrlService;
use Illuminate\Support\Facades\DB;

// Configuration
$ttl = 600; // 10 minutes validity
$baseUrl = url('/feedback');

// Generate 10-digit numeric token
$tokenNumber = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);

try {
    // Insert token into database (adjust table name as needed)
    // If you have a signupaction table:
    if (DB::getSchemaBuilder()->hasTable('signupaction')) {
        DB::table('signupaction')->insert([
            'action' => $tokenNumber,
            'created_at' => now(),
        ]);
    }

    // Generate signed URL
    $signedUrlService = new SignedUrlService();
    $url = $signedUrlService->generateSignedUrl($tokenNumber, $baseUrl);

    // Output
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Generate Feedback Link</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
            h2 { color: #0f2650; }
            .url-box { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0; word-break: break-all; }
            .token-box { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 20px 0; }
            a { color: #e63946; text-decoration: none; }
            a:hover { text-decoration: underline; }
            .copy-btn { background: #0f2650; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
            .copy-btn:hover { background: #1a3d6b; }
        </style>
    </head>
    <body>
        <h2>Generated Feedback Link</h2>
        <p>Click the link to open feedback form (valid for <?= $ttl ?> seconds / <?= round($ttl/60, 1) ?> minutes):</p>
        
        <div class="url-box">
            <strong>Feedback URL:</strong><br>
            <a href="<?= htmlspecialchars($url) ?>" target="_blank" id="feedbackLink"><?= htmlspecialchars($url) ?></a>
            <br>
            <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($url) ?>')">Copy URL</button>
        </div>

        <div class="token-box">
            <strong>10-Digit Token Number:</strong> <?= htmlspecialchars($tokenNumber) ?>
            <br>
            <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($tokenNumber) ?>')">Copy Token</button>
        </div>

        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Copied to clipboard!');
                }, function(err) {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Copied to clipboard!');
                });
            }
        </script>
    </body>
    </html>
    <?php

} catch (\Exception $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}

