<?php

namespace App\Enums;

enum ProfitStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
}