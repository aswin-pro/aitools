<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AIToolsUpdater400
{
    public function runUpdate()
    {
        // Check "activation_email_address" column is exist in "configs" table or not
        if (DB::table('configs')->where('config_key', 'activation_email_address')->count() == 0) {
            DB::statement("INSERT INTO `configs` (`config_key`, `config_value`) VALUES ('activation_email_address', NULL)");
        }
    }
}
