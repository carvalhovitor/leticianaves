<?php

return function ($page) {
    $gallery = $page->images()->template('image');

    return [
        'gallery' => $gallery
    ];
};

?>