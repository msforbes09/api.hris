<?php
// Modules Resource Routes
Route::apiResources(
    [
        'users' => 'Api\UserController',
        'clients' => 'Api\ClientController',
        'clients.branches' => 'Api\ClientBranchController',
        'clients.positions' => 'Api\ClientPositionController',
        'keywords' => 'Api\KeywordController',
        'applicants' => 'Api\ApplicantController',
        'applicants.families' => 'Api\ApplicantFamilyController',
        'applicants.education' => 'Api\ApplicantEducationController',
        'applicants.employments' => 'Api\ApplicantEmploymentController',
        'applicants.applications' => 'Api\ApplicationController',
        'applications' => 'Api\ApplicationController',
        'sms-templates' => 'Api\SmsTemplateController'
    ]
);