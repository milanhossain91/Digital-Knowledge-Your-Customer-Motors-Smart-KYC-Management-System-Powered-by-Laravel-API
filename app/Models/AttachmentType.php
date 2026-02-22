<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachmentType extends Model
{
    use HasFactory;

    protected $table = 'attachment_types';
    protected $primaryKey = 'AttachmentTypeID';
    public $timestamps = true;

    protected $fillable = ['AttachmentType', 'Active'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'AttachmentTypeID', 'AttachmentTypeID');
    }
}
