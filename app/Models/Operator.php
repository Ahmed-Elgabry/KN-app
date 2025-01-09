<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Operator extends Model
{
    use LogsActivity;

    public $table = 'operators';
    public $timestamps = false;
    protected $fillable = ['id','name','phone','created_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
