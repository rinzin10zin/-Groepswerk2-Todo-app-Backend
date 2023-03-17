<?php

function makeBody($required_filters, $other_filters)
{
    $filters = [];

    foreach ($_REQUEST as $key) {
        if (!in_array($key, $required_filters) && !in_array($key, $other_filters)) {
            return false;
        }
    }
    foreach ($required_filters as $filter) {
        if (isset($args[$filter])) {
            $filters[$filter] = $args[$filter];
        }
    }

    return $filters;
}
