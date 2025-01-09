<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class bill extends GlobalModel
{
    use LogsActivity;

    public $table = 'bill';
    public $timestamps = false;
    protected $fillable = ['id','operator_id','updated_at','created_at'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'id');
    }

    public function billItems()
    {
        return $this->hasMany(BillItems::class, 'bill_id', 'id');
    }
}
