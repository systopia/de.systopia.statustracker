<?php
/*-------------------------------------------------------+
| SYSTOPIA Status Tracker Extension                      |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Statustracker_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Statustracker_Upgrader extends CRM_Extension_Upgrader_Base {

  /**
   * Installer
   */
  public function install() {
    // run the custom group sync
    require_once 'CRM/Statustracker/CustomData.php';
    $customData = new CRM_Statustracker_CustomData('de.systopia.statustracker');
    $customData->syncOptionGroup(__DIR__ . '/../../resources/status_tracker_category_option_group.json');
    $customData->syncOptionGroup(__DIR__ . '/../../resources/status_tracker_status_option_group.json');
    $customData->syncCustomGroup(__DIR__ . '/../../resources/status_tracker_custom_group.json');

    return TRUE;
  }

  /**
   * Update to version 0.2
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_0020() {
    $this->ctx->log->info('Updating to version 0.2...');

    // add deadline filed
    require_once 'CRM/Statustracker/CustomData.php';
    $customData = new CRM_Statustracker_CustomData('de.systopia.statustracker');
    $customData->syncCustomGroup(E::path('resources/status_tracker_custom_group.json'));

    return TRUE;
  }
}