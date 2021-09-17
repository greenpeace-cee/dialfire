<?php
namespace Civi\Api4;

use Civi\Api4\Action\DialfireTenant\Synchronize;

/**
 * DialfireTenant entity.
 *
 * Provided by the dialfire extension.
 *
 * @package Civi\Api4
 */
class DialfireTenant extends Generic\DAOEntity {

  /**
   * Synchronize DialfireTenant via API
   *
   * @param bool $checkPermissions
   * @return \Civi\Api4\Action\DialfireTenant\Synchronize
   */
  public static function synchronize($checkPermissions = TRUE) {
    $action = new Synchronize(__CLASS__, __FUNCTION__);
    return $action->setCheckPermissions($checkPermissions);
  }

}
