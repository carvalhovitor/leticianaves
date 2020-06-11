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

    </section>

<?php snippet('foot'); ?>