<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachments';
    protected $primaryKey = 'AttachmentID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerInfoID', 'AttachmentTypeID', 'AttachmentRaw', 'AttachmentOriginal', 'CreatedBy', 'CreatedAt'
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerInfo::class, 'CustomerInfoID', 'CustomerInfoID');
    }

    public function type()
    {
        return $this->belongsTo(AttachmentType::class, 'AttachmentTypeID', 'AttachmentTypeID');
    }
}
