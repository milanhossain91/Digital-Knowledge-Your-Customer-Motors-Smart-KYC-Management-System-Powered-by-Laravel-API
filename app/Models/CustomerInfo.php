<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    use HasFactory;

    protected $table = 'customer_infos';
    protected $primaryKey = 'CustomerInfoID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerCode', 'CustomerName', 'Business', 'FatherName', 'Address',
        'Contact', 'NID', 'downPayment', 'FinanceAmount', 'OutstandingAmount', 'MaturedAmount', 'NonMaturedAmount', 'OverDueTaka', 'NoOfInstallment', 'InvoiceDate',
        'TTYCode', 'TTYName', 'BoxNo', 'CreatedBy', 'CreatedAt'
    ];

    // Relationship: Customer has many attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'CustomerInfoID', 'CustomerInfoID');
    }
}
