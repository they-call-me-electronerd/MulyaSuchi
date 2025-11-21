<?php
/**
 * Common Header
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_TAGLINE; ?> - Real-time market prices for items across Nepal">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>
    
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/responsive.css">
    <?php if (isset($additionalCSS)): ?>
        <link rel="stylesheet" href="<?php echo SITE_URL . '/assets/css/' . $additionalCSS; ?>">
    <?php endif; ?>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    $flash = getFlashMessage();
    if ($flash):
    ?>
    <div class="flash-message flash-<?php echo $flash['type']; ?>" id="flashMessage">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
    <?php endif; ?>
