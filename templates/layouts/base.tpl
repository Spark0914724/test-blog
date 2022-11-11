{* Smarty *}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:'Blogy.'}</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php?page=home" class="logo">Blogy.</a>
        </div>
    </header>
    <main class="main">
        <div class="container">
            {block name="content"}{/block}
        </div>
    </main>
    <footer class="site-footer">
        <div class="container">
            <p>Copyright &copy;2026. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
