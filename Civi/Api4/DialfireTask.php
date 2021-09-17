<?php
namespace Civi\Api4;

use Civi\Api4\Action\DialfireTask\Synchronize;

/**
 * DialfireTask entity.
 *
 * Provided by the dialfire extension.
 *
 * @package Civi\Api4
 */
class DialfireTask extends Generic\DAOEntity {

  /**
   * Synchronize DialfireTask via API
   *
   * @param bool $checkPermissions
   * @return \Civi\Api4\Action\DialfireTask\Synchronize
   */
  public static function synchronize($checkPermissions = TRUE) {
    $action = new Synchronize(__CLASS__, __FUNCTION__);
    return $action->setCheckPermissions($checkPermissions);
  }

}
