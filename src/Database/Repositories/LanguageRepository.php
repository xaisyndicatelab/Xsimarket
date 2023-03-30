<?php


namespace Xsimarket\Database\Repositories;

use Xsimarket\Database\Models\Language;

class LanguageRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Language::class;
    }
}
