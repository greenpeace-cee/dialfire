<?php
namespace Civi\Api4;

use Civi\Api4\Action\DialfireCampaign\Synchronize;

/**
 * DialfireCampaign entity.
 *
 * Provided by the dialfire extension.
 *
 * @package Civi\Api4
 */
class DialfireCampaign extends Generic\DAOEntity {

  /**
   * Synchronize DialfireCampaign via API
   *
   * @param bool $checkPermissions
   * @return \Civi\Api4\Action\DialfireCampaign\Synchronize
   */
  public static function synchronize($checkPermissions = TRUE) {
    $action = new Synchronize(__CLASS__, __FUNCTION__);
    return $action->setCheckPermissions($checkPermissions);
  }

}
