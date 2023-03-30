<?php


namespace Xsimarket\Database\Repositories;


use Xsimarket\Database\Models\Attachment;

class AttachmentRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Attachment::class;
    }
}
