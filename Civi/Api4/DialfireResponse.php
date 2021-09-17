<?php
namespace Civi\Api4;

/**
 * DialfireResponse entity.
 *
 * Provided by the dialfire extension.
 *
 * @package Civi\Api4
 */
class DialfireResponse extends Generic\DAOEntity {
  /**
   * @param bool $checkPermissions
   * @return Action\DialfireResponse\Create
   */
  public static function create($checkPermissions = TRUE) {
    return (new Action\DialfireResponse\Create(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }
}
