<?php snippet('head'); ?>

<?php snippet('header'); ?>

    <section class="project-title text">
        <?= $page->title(); ?>
    </section>

    <section class="project-description">
        <div class="project-description-wrapper text">
            <?= $page->text()->kirbyText(); ?>
        </div>
    </section>

    <?php foreach ($page->images() as $image): ?>
        <section class="project-image">
            <?= $image ?>
        </section>
    <?php endforeach ?>

<?php snippet('foot'); ?>