-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
---- /*******************************************************
-- *
-- * Clean up the existing tables-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_dialfire_response`;
DROP TABLE IF EXISTS `civicrm_dialfire_contact`;
DROP TABLE IF EXISTS `civicrm_dialfire_task`;
DROP TABLE IF EXISTS `civicrm_dialfire_campaign`;
DROP TABLE IF EXISTS `civicrm_dialfire_tenant`;

SET FOREIGN_KEY_CHECKS=1;