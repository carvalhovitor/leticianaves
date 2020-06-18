<header>
    <nav>
        <a href="/"><span <?= e($page->isHomePage(), 'class="active"') ?>>Portfólio</span></a> 
        <a class="logotype" href="/"><?php snippet('logotype'); ?></a>
        <a href="/sobre"><span <?= e($page->is('sobre'), 'class="active"') ?>>Sobre</span></a>
    </nav>
</header>

<main id="main">

<section class="grid">