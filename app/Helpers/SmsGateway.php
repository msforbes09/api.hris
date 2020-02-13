<?php


namespace App\Helpers;


interface SmsGateway
{
    public function send($applicant);
}
