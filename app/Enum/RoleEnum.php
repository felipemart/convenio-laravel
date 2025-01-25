<?php

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN      = 'admin';
    case OPERADORA  = 'empresas';
    case CONVENIO   = 'convenio';
    case CONVENIADA = 'conveniada';

}
