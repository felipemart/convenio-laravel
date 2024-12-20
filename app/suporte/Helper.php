<?php

function obfuscar_email(string $email): string
{
    $split = explode('@', $email);
    $qt    = floor(strlen($split[0]) * 0.75);
    $resto = strlen($split[0]) - $qt;

    $split[0] = substr($split[0], 0, $resto) . str_repeat('*', $qt);

    return implode('@', $split);
}
