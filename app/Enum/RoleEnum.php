<?php

declare(strict_types = 1);

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN      = 'admin';
    case OPERADORA  = 'empresas';
    case CONVENIO   = 'convenio';
    case CONVENIADA = 'conveniada';
}
