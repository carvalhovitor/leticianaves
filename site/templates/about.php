<?php snippet('head'); ?>
        
    <?php snippet('header'); ?>

    <section class="grid">

        <section class="about-me">
            <div class="about-me-wrapper">
                <?= $page->text()->kirbytext() ?>
            </div>
        </section>

        <section class="contact">
            <div class="contact-wrapper">
                <?= $page->contact()->kirbytext() ?>
            </div>
        </section>

        <section class="profile-01">
            <img src="assets/profile/ln-01.jpg">
        </section>
        
        <section class="profile-02">
            <img src="assets/profile/ln-02.jpg">
        </section>

        <?php $xps = $page->professional()->toStructure(); ?>

        <section class="title" style="grid-row-end: span <?= $xps->count(); ?>"><p>Experiência Profissional</p></section>

        <section class="cv" style="grid-row-end: span <?= $xps->count(); ?>">

            <?php foreach ($xps as $xp): ?>

                <section class="cv-entry">
                    <section class="date"><?= $xp->date()->html() ?></section>
                    <section class="description"><?= $xp->description() ?></section>
                </section>

            <?php endforeach ?>

        </section>

    </section>

<?php snippet('foot'); ?>