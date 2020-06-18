<?php snippet('head'); ?>
        
    <?php snippet('header'); ?>

        <?php foreach($projects as $project): ?>
            <a class="project-card" href="<?= $project->url() ?>">
                <div class="project-cover">
                    <?= $project->cover()->toFile(); ?>
                </div>
                <div class="project-info">
                    <h2><?= $project->title(); ?></h2>
                    <p class="categories">
                    <?php $i = 1; $categories = $project->categories()->split(); ?>
                    <?php foreach ($categories as $category): ?>
                        <?= option('categoryMap')[$category] ?><?= $i < count($categories) ? ',' : '' ?>
                        <?php $i++ ?>
                    <?php endforeach ?>
                    </p>
                </div>
            </a>
        <?php endforeach ?>
                
    <?php snippet('foot'); ?>