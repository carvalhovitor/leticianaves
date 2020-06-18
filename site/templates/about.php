<?php snippet('head'); ?>
        
    <?php snippet('header'); ?>

        <section class="about-me-wrapper">
            <div class="about-me text">
                <?= $page->text()->kirbytext() ?>
            </div>
        </section>

        <section class="contact-wrapper">
            <div class="contact text">
                <?= $page->contact()->kirbytext() ?>
            </div>
        </section>

        <section class="profile-01">
            <img src="assets/profile/ln-01.jpg">
        </section>
        
        <section class="profile-02">
            <img src="assets/profile/ln-02.jpg">
        </section>

<?php snippet('foot'); ?>