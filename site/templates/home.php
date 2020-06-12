<?php snippet('head'); ?>
        
    <?php snippet('header'); ?>

                <?php foreach($projects as $project): ?>
                    <div class="project-card">
                        <a href="<?= $project->url() ?>">
                            <?= $project->cover()->toFile(); ?>
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
                    </div>
                <?php endforeach ?>

                <div class="project-card">
                    <img src="https://vitorcarvalho.com/content/1-home/1-bdmg-cultural/bdmg-04.jpg"></img>
                    <div class="project-info">
                        <h2>Nome do projeto</h2>
                        <p>Tags</p>
                    </div>
                </div>
                <div class="project-card">
                    <img src="https://vitorcarvalho.com/content/1-home/1-bdmg-cultural/bdmg-11.jpg"></img>
                    <div class="project-info">
                        <h2>Nome do projeto</h2>
                        <p>Tags</p>
                    </div>
                </div>
                <div class="project-card">
                    <img src="https://vitorcarvalho.com/content/1-home/1-bdmg-cultural/bdmg-11.jpg"></img>
                    <div class="project-info">
                        <h2>Nome do projeto</h2>
                        <p>Tags</p>
                    </div>
                </div>
                <div class="project-card">
                    <img src="https://vitorcarvalho.com/content/1-home/1-bdmg-cultural/bdmg-04.jpg"></img>
                    <div class="project-info">
                        <h2>Nome do projeto</h2>
                        <p>Tags</p>
                    </div>
                </div>
                
    <?php snippet('foot'); ?>