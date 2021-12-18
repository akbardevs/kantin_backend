<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TransactionPolicy
{
    use HandlesAuthorization;
}
