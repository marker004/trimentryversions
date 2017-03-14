<?php
namespace Craft;

class TrimEntryVersionsController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * Deletes all entry versions.
     *
     * @return null
     */
    public function actionDelete()
    {
        $number_to_keep = intval($_POST['numberToTruncate']);
        $initial_memory = ini_get('memory_limit');

        ini_set('memory_limit', '1024M');

        if (craft()->getEdition() >= Craft::Client) {

            if ($number_to_keep == 0) {
              // Remove Foreign Key Constraints
              craft()->db->createCommand("SET foreign_key_checks = 0;")->execute();

              // Delete them all.
              craft()->db->createCommand()->truncateTable('entryversions');

              // Re-add Foreign Key Constraints
              craft()->db->createCommand("SET foreign_key_checks = 1;")->execute();
            }

            if ($number_to_keep > 0) {

                $builder = array();
                $all_versions = craft()->db->createCommand()
                  ->from('entryversions')
                  ->queryAll();

                foreach ($all_versions as $version) {
                  if ($version['num'] > $number_to_keep) {
                    if (!array_key_exists($version['entryId'], $builder)) {
                      $builder[$version['entryId']] = 1;
                    }
                    else {
                      $builder[$version['entryId']] ++;
                    }
                  }
                }

                foreach ($builder as $entry => $number_to_truncate) {
                  $delete_rows = craft()->db->createCommand("DELETE FROM craft_entryversions WHERE `entryId` = ". $entry ." AND `num` < ".$number_to_truncate.";")->execute();
                  $update_rows = craft()->db->createCommand("UPDATE craft_entryversions SET `num` = `num` - ".($number_to_truncate - 1)." WHERE `entryId` = ". $entry ." AND `num` >= ".$number_to_truncate.";")->execute();

                }

            }
        }

        ini_set('memory_limit', $initial_memory);

        $this->redirectToPostedUrl();
    }
}
