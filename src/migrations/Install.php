<?php

namespace vaersaagod\locate\migrations;

use craft\db\Migration;

/**
 * Class Install
 * @package vaersaagod\locate\migrations
 */
class Install extends Migration
{

    /**
     * @return bool
     */
    public function safeUp()
    {
        return (new m200828_223834_migrate_fields())->safeUp();
    }

}
