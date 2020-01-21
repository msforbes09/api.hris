<?php

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    protected $modules = [
        ['code'=>'user', 'name' => 'User Module',
            'actions' => ['view', 'create', 'show', 'update', 'delete']
        ],
        ['code'=>'access', 'name' => 'Access Module',
            'actions' => ['view', 'show', 'update']
        ],
        ['code'=>'keyword', 'name' => 'Keyword Module',
            'actions' => ['show', 'create', 'update', 'delete']
        ],
    ];

    public function run()
    {
        foreach ($this->modules as $module) {
            $id = DB::table('modules')->insertGetId([
                'code' => $module['code'],
                'name' => $module['name'],
            ]);

            foreach ($module['actions'] as $action) {
                DB::table('module_actions')->insert([
                    'module_id' => $id,
                    'code' => $action,
                    'name' => $action,
                ]);
            }
        }
    }
}
