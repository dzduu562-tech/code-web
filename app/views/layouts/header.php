<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? SITE_TITLE ?> - <?= SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>/images/favicon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
    
    <!-- Theme CSS -->
    <style>
        :root[data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --shadow: rgba(0, 0, 0, 0.1);
        }
        
        :root[data-theme="dark"] {
            --bg-primary: #1a1d29;
            --bg-secondary: #252835;
            --bg-tertiary: #2d3142;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --border-color: #495057;
            --shadow: rgba(0, 0, 0, 0.3);
        }
        
        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .card, .modal-content, .dropdown-menu {
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .navbar {
            background-color: var(--bg-primary) !important;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar {
            background-color: var(--bg-primary);
            border-right: 1px solid var(--border-color);
        }
        
        .text-muted {
            color: var(--text-secondary) !important;
        }
    </style>
</head>
<body>
