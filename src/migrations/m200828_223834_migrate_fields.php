<?php

namespace vaersaagod\locate\migrations;

use craft\db\Migration;
use vaersaagod\locate\fields\LocateField;

/**
 * Class m200828_223834_migrate_fields
 * @package vaersaagod\locate\migrations
 * @since 2.3.0
 */
class m200828_223834_migrate_fields extends Migration
{

    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        // Migrate the old swixpop/locate field
        $this->update('{{%fields}}', [
            'type' => LocateField::class
        ], ['type' => 'swixpop\\locate\\fields\\LocateField']);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        echo "m200828_223834_migrate_fields cannot be reverted.\n";
        return false;
    }

}
