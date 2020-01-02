<?php

namespace App\Contracts;

interface IModule
{
    public function all();

    public function getById();

    public function destroy();
}