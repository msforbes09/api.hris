<?php

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    protected $modules = [
        ['code'=>'user', 'name' => 'User Module',
            'actions' => ['view', 'create', 'show', 'update',]
        ],
        ['code'=>'access', 'name' => 'Access Module',
            'actions' => ['view', 'show', 'update']
        ],
        ['code'=>'keyword', 'name' => 'Keyword Module',
            'actions' => ['view', 'show', 'create', 'update', 'delete']
        ],
        ['code' => 'client', 'name' => 'Client Module',
            'actions' => ['view', 'show', 'create', 'update', 'delete', 'restore']
        ],
        ['code' => 'applicant', 'name' => 'Applicant Module',
            'actions' => ['view', 'show', 'create', 'update', 'delete']
        ],
        ['code' => 'sms', 'name' => 'SMS Module',
            'actions' => ['view', 'send']
        ],
        ['code' => 'sms_template', 'name' => 'SMS Template Module',
            'actions' => ['view', 'show', 'create', 'update', 'delete']
        ],
        ['code' => 'audit', 'name' => 'Audit Module',
            'actions' => ['view']
        ]
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
