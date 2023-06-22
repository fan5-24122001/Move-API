<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Address\AddressRepository;

class AddressController extends Controller
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function getCountries()
    {
        return $this->addressRepository->getCountries();
    }

    public function getStates($id)
    {
        return $this->addressRepository->getStates($id);
    }
}
