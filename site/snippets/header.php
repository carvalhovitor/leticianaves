<header>
    <nav>
        <a <?= e($page->isHomePage(), 'class="active"') ?> href="/">Portfólio</a> 
        <a class="logotype" href="/"><?php snippet('logotype'); ?></a>
        <a <?= e(!$page->isHomePage(), 'class="active"') ?> href="/sobre">Sobre</a>
    </nav>
</header>

<main id="main">

<section class="grid">