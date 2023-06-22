<?php

namespace App\Repositories\Address;

interface AddressInterface
{
    public function getCountries();

    public function getStates($id);
}
