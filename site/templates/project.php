<?php snippet('head'); ?>

<?php snippet('header'); ?>

    <section class="project-title text">
        <?= $page->title(); ?>
    </section>

    <section class="project-description-wrapper">
        <div class="project-description text">
            <?= $page->text()->kirbyText(); ?>
        </div>
    </section>


    <?php foreach ($gallery as $image): ?>
        <section class="project-image">
            <?= $image ?>
        </section>
    <?php endforeach ?>

<?php snippet('foot'); ?>