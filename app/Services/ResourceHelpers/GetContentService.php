<?php

namespace App\Services\ResourceHelpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Service;
use App\Services\Users\CheckViewPrivilegeService;
use App\Services\ResourceHelpers\ListService;
use App\Services\ResourceHelpers\ListPaginateService;
use App\Services\ResourceHelpers\OneService;

class GetContentService extends Service
{
  public function __construct(string $resourceType)
  {
    $this->errorMsg = __("resources")["errors"]["post_not_allowed"];

    $this->resourceType = $resourceType;

    $this->checkPrivilegeSvc = new CheckViewPrivilegeService();
  }

  public function execute()
  {
  }

  /**
   * Returns content (manuals etc) based on user privilege (role/auth status/group).
   */
  public function list($catId = null)
  {
    // $listSvc = new ListService($this->resourceType);
    $listSvc = new ListPaginateService($this->resourceType);

    switch ($this->checkPrivilegeSvc->execute()) {
      case "SUPER_ADMIN":
        return $listSvc->all($catId);
      case "GUEST":
        return $listSvc->forPublic($catId);
      default:
        return $listSvc->forUserWithGroup(Session::get("user")["user_group_id"], $catId); // prettier-ignore
    }
  }

  public function searchTitle($keyword)
  {
    $searchSvc = new SearchTitleService($this->resourceType);

    switch ($this->checkPrivilegeSvc->execute()) {
      case "SUPER_ADMIN":
        return $searchSvc->all($keyword);
      case "GUEST":
        return $searchSvc->forPublic($keyword);
      default:
        return $searchSvc->forUserWithGroup(Session::get("user")["user_group_id"], $keyword); // prettier-ignore
    }
  }

  public function filterDistrict($districtId)
  {
    $districtSvc = new FilterDistrictService($this->resourceType);

    switch ($this->checkPrivilegeSvc->execute()) {
      case "SUPER_ADMIN":
        return $districtSvc->all($districtId);
      case "GUEST":
        return $districtSvc->forPublic($districtId);
      default:
        return $districtSvc->forUserWithGroup(Session::get("user")["user_group_id"], $districtId); // prettier-ignore
    }
  }

  public function one($id)
  {
    $oneSvc = new OneService($this->resourceType);
    $one = $oneSvc->get($id);

    if (
      $this->canUserSeePost(
        $one["available_for"] ? $one["available_for"] : 0,
        $one["user"],
        $this->checkPrivilegeSvc->execute(),
        Session::get("user") ? Session::get("user")["user_group_id"] : null
      )
    ) {
      return $one;
    } else {
      throw new \Exception($this->errorMsg);
    }
  }

  private function canUserSeePost(
    int $availableFor,
    $author,
    $userStatus,
    $userGroupId = null
  ) {
    switch ($availableFor) {
      case 0:
        return true;
      case 1:
        if (!$author || $userStatus === "GUEST") {
          return false;
        } elseif ($userStatus === "SUPER_ADMIN") {
          return true;
        } elseif ($userGroupId && $userGroupId === $author["user_group_id"]) {
          return true;
        }
        return false;
      case 2:
        return $userStatus !== "GUEST";
      default:
        return false;
    }
  }
}
