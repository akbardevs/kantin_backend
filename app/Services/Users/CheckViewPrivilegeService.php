<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Services\Exceptions\InvalidParameterException;
use App\Models\User;
use App\Services\Service;

class CheckViewPrivilegeService extends Service
{
  public function __construct()
  {
  }

  /**
   * Returns relevant user role/auth status/group to define the content they can view.
   */
  public function execute(): string
  {
    $user = Session::get("user");
    if (!$user) return "GUEST"; // prettier-ignore
    if ($user["role"] === "SUPER_ADMIN") return "SUPER_ADMIN"; // prettier-ignore
    return "OTHER";
  }
}
