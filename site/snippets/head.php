<!DOCTYPE html>
<html lang="<?= $kirby->language(); ?>">

<head>
    <meta charset="utf-8">
    <title><?= $site->title() ?></title>
    <meta name="description" content="<?= $site->description() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1"/>
    <?= css('/assets/css/index.css') ?>
</head>

<body class="<?= $page->template() ?>">
    <div class="wrapper">