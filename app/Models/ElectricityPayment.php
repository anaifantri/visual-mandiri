<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ElectricityPayment extends Model
{
    use Sortable;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public $sortable = ['payment_date', 'bill_date'];
}
