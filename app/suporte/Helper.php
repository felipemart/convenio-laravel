<?php

declare(strict_types = 1);

function obfuscarEmail(?string $email): string
{
    if ($email === null || $email === '' || $email === '0') {
        return '';
    }

    $split = explode('@', $email);

    if (count($split) != 2) {
        return '';
    }

    $qt    = floor(strlen($split[0]) * 0.75);
    $resto = strlen($split[0]) - $qt;

    $split[0] = substr($split[0], 0, intval($resto)) . str_repeat('*', intval($qt));

    return implode('@', $split);
}
