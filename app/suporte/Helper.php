<?php

function obfuscar_email(?string $email): string
{
    if (empty($email)) {
        return '';
    }

    $split = explode('@', $email);

    if (sizeof($split) != 2) {
        return '';
    }

    $qt    = floor(strlen($split[0]) * 0.75);
    $resto = strlen($split[0]) - $qt;

    $split[0] = substr($split[0], 0, $resto) . str_repeat('*', $qt);

    return implode('@', $split);
}
