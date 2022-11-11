{* Smarty *}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:'Blog'}</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php?page=home" class="logo">Blog</a>
            <nav class="nav">
                <a href="index.php?page=home">Home</a>
            </nav>
        </div>
    </header>
    <main class="main container">
        {block name="content"}{/block}
    </main>
    <footer class="site-footer">
        <div class="container">
            <p>&copy; {assign var="year" value=$smarty.now|date_format:"%Y"}{$year} Simple Blog. PHP + Smarty + MySQL.</p>
        </div>
    </footer>
</body>
</html>
