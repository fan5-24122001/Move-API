<?php 

namespace App\Repositories\Address;

use App\Models\Country;
use App\Models\State;
use App\Traits\JsonResponseTrait;

class AddressRepository implements AddressInterface
{
    use JsonResponseTrait;

    private Country $country;
    private State $state;

    public function __construct(Country $country, State $state)
    {
        $this->country = $country;
        $this->state = $state;
    }
    public function getCountries()
    {
        $countries = $this->country->all();

        return $this->result($countries, 200, true);
    }

    public function getStates($id)
    {
        $states = $this->state->where('country_id', $id)->get();

        return $this->result($states, 200, true);
    }
}
