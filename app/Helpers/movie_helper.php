<?php

if (! function_exists('movieSlug')) {

    function movieSlug(string $title): string
    {
        helper('url');

        return url_title(
            strtolower($title),
            '-',
            true
        );
    }
}
