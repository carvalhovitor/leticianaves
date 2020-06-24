<?php

return function ($page) {
    $gallery = $page->images()->template('image')->sortBy('sort');

    return [
        'gallery' => $gallery
    ];
};

?>