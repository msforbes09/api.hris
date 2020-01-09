<?php

use Illuminate\Database\Seeder;

class __ModuleTableSeeder extends Seeder
{
    protected $modules = [
        ['code'=>'001', 'name' => 'User Module'],
        ['code'=>'002', 'name' => 'Client Module'],
    ];

    protected $actions = [
        ['code' => 'V', 'name' => 'view'],
        ['code' => 'S', 'name' => 'store'],
        ['code' => 'SH', 'name' => 'show'],
        ['code' => 'U', 'name' => 'update'],
        ['code' => 'D', 'name' => 'destroy']
    ];

    public function run()
    {
        $this->deleteExisting();

        foreach ($this->modules as $module) {
            $id = DB::table('modules')->insertGetId($module);

            foreach ($this->actions as $action) {
                $action['module_id'] = $id;
                $action['code'] .= '-' . $module['code'];
                DB::table('module_actions')->insert($action);
            }
        }
    }

    protected function deleteExisting()
    {
        DB::table('module_actions')->delete();
        DB::table('modules')->delete();
    }
}
