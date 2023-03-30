<?php


namespace Xsimarket\Database\Repositories;

use Xsimarket\Database\Models\Address;

class AddressRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Address::class;
    }
}
