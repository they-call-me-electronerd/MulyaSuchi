<?php
/**
 * Test Image Upload
 */

define('MULYASUCHI_APP', true);
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$message = '';
$imagePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $result = uploadImage($_FILES['test_image']);
    
    if ($result['success']) {
        $message = "✅ Success! Image uploaded as: " . $result['filename'];
        $imagePath = $result['filename'];
    } else {
        $message = "❌ Error: " . $result['error'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Image Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .upload-box {
            border: 2px dashed #ccc;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
            cursor: pointer;
        }
        .upload-box:hover {
            border-color: #f97316;
            background: #fff7ed;
        }
        .preview {
            margin-top: 20px;
            text-align: center;
        }
        .preview img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            border: 3px solid #f97316;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 600;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        .btn {
            background: #f97316;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #ea580c;
        }
        .info {
            background: #eff6ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🖼️ Image Upload Test</h1>
        <p>Test the image upload functionality</p>
        
        <div class="info">
            <strong>Upload Directory:</strong> <?php echo UPLOAD_DIR; ?><br>
            <strong>Directory Exists:</strong> <?php echo is_dir(UPLOAD_DIR) ? '✅ Yes' : '❌ No'; ?><br>
            <strong>Directory Writable:</strong> <?php echo is_writable(UPLOAD_DIR) ? '✅ Yes' : '❌ No'; ?><br>
            <strong>GD Extension:</strong> <?php echo extension_loaded('gd') ? '✅ Enabled' : '❌ Disabled'; ?><br>
            <strong>Max Upload Size:</strong> <?php echo ini_get('upload_max_filesize'); ?><br>
            <strong>Max Post Size:</strong> <?php echo ini_get('post_max_size'); ?>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $result['success'] ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="upload-box" onclick="document.getElementById('test_image').click()">
                <input type="file" id="test_image" name="test_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #f97316;"></i>
                <p id="file-name" style="margin: 10px 0 0 0; font-weight: 600;">Click to select an image</p>
            </div>
            
            <div id="preview" class="preview" style="display: none;">
                <p style="font-weight: 600; margin-bottom: 10px;"><i class="bi bi-eye"></i> Preview</p>
                <img id="preview-img" src="" alt="Preview">
                <p id="file-info" style="margin-top: 10px; color: #666;"></p>
            </div>
            
            <button type="submit" class="btn">
                <i class="bi bi-upload"></i> Upload Image
            </button>
        </form>
        
        <?php if ($imagePath): ?>
            <div style="margin-top: 30px; padding: 20px; background: #f0fdf4; border-radius: 8px; border: 2px solid #10b981;">
                <h3 style="color: #065f46; margin-top: 0;">Uploaded Image</h3>
                <p><strong>Filename:</strong> <?php echo htmlspecialchars($imagePath); ?></p>
                <p><strong>Full Path:</strong> <?php echo UPLOAD_DIR . htmlspecialchars($imagePath); ?></p>
                <p><strong>File Exists:</strong> <?php echo file_exists(UPLOAD_DIR . $imagePath) ? '✅ Yes' : '❌ No'; ?></p>
                <img src="<?php echo SITE_URL; ?>/assets/uploads/items/<?php echo htmlspecialchars($imagePath); ?>" 
                     alt="Uploaded" 
                     style="max-width: 100%; max-height: 400px; border-radius: 8px; margin-top: 10px;">
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    function previewImage(input) {
        const fileNameDisplay = document.getElementById('file-name');
        const previewContainer = document.getElementById('preview');
        const previewImg = document.getElementById('preview-img');
        const fileInfo = document.getElementById('file-info');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            fileNameDisplay.textContent = '✅ ' + file.name;
            
            const fileSizeKB = (file.size / 1024).toFixed(2);
            const fileSizeMB = (file.size / 1048576).toFixed(2);
            const sizeText = file.size > 1048576 ? `${fileSizeMB} MB` : `${fileSizeKB} KB`;
            fileInfo.textContent = `Size: ${sizeText} | Type: ${file.type}`;
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        }
    }
    </script>
</body>
</html>
