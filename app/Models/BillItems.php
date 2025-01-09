<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BillItems extends Model
{
    use LogsActivity;

    public $table = 'bill_items';
    public $timestamps = false;
    protected $fillable = ['id','bill_id','type_id','type_name', 'type_price','created_at'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
