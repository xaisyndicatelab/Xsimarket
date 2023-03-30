<?php


namespace Xsimarket\Database\Repositories;

use Xsimarket\Database\Models\DeliveryTime;

class DeliveryTimeRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTime::class;
    }
}
