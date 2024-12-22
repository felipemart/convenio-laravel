<?php

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN      = 'admin';
    case OPERADORA  = 'operadora';
    case CONVENIO   = 'convenio';
    case CONVENIADA = 'conveniada';

}
