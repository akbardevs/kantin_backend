<?php

namespace App\Services\ResourceHelpers;

use App\Services\Service;

class GetContentFromPaginationService extends Service
{
  public function __construct()
  {
  }

  public function execute()
  {
  }

  public function get($pagination)
  {
    if (array_key_exists("data", $pagination)) {
      $data = $pagination["data"];
      unset($pagination["data"]);
    } else {
      $data = [];
    }
    return [$pagination, $data];
  }
}
