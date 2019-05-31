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

class CRM_Statustracker_Page_Dashboard extends CRM_Core_Page {

  public function run() {
    // get contact ID
    // TODO: get from parameter?
    $contact_id = (int) CRM_Core_Session::getLoggedInContactID();

    // get some data
    $category_lookup = $this->getOptionGroup('status_tracker_category');
    $status_lookup   = $this->getOptionGroup('status_tracker_status');

    // collect projects
    $processlist = [];
    $query = CRM_Core_DAO::executeQuery("
      SELECT
       tracker.entity_id   AS contact_id,
       tracker.category    AS category_id,
       tracker.title       AS title,
       tracker.status      AS status_id,
       tracker.change_date AS change_date,
       tracker.link        AS link,
       tracker.note        AS note
      FROM civicrm_value_status_tracker tracker
      WHERE lead_contact_id = {$contact_id}
      GROUP BY tracker.id
      ORDER BY change_date DESC;");
    while ($query->fetch()) {
      $processlist[] = [
          'contact_id'  => $query->contact_id,
          'category_id' => $query->category_id,
          'category'    => CRM_Utils_Array::value($query->category_id, $category_lookup, ''),
          'status_id'   => $query->status_id,
          'status'      => CRM_Utils_Array::value($query->status_id, $status_lookup, ''),
          'title'       => $query->title,
          'link'        => $query->link,
          'note'        => $query->note,
          'change_date' => $query->change_date
      ];
    }

    // add contact images
    $contact_ids = [];
    foreach ($processlist as $process) {
      $contact_ids[] = $process['contact_id'];
    }
    if (!empty($contact_ids)) {
      $contacts = civicrm_api3('Contact', 'get', [
          'id'           => ['IN' => $contact_ids],
          'sequential'   => 0,
          'return'       => 'contact_type,contact_sub_type,display_name,id',
          'option.limit' => 0])['values'];
      // add contact links
      foreach ($processlist as &$process) {
        $contact = $contacts[$process['contact_id']];
        $process['contact_name']  = $contact['display_name'];
        $process['contact_link']  = CRM_Utils_System::url("civicrm/contact/view", 'reset=1&cid=' . $contact['id']);
        $process['contact_image'] = CRM_Contact_BAO_Contact_Utils::getImage(empty($contact['contact_sub_type']) ? $contact['contact_type'] : $contact['contact_sub_type'], FALSE, $contact['id']);
      }
    }

    $this->assign('processlist', $processlist);
    if (count($processlist)) {
      CRM_Utils_System::setTitle(E::ts('Process Status Dashboard: %1 processes found', [1 => count($processlist)]));
    } else {
      CRM_Utils_System::setTitle(E::ts('Process Status Dashboard: none found'));
    }

    parent::run();
  }


  /**
   * Returns a value -> label mapping of the given option group
   *
   * @param $option_group_name string option group name
   * @return array
   */
  protected function getOptionGroup($option_group_name) {
    $options = [];
    $query = civicrm_api3('OptionValue', 'get', [
        'option_group_id' => $option_group_name,
        'return'          => 'value,label',
        'option.limit'    => 0]);
    foreach ($query['values'] as $option_value) {
      $options[$option_value['value']] = $option_value['label'];
    }
    return $options;
  }
}
