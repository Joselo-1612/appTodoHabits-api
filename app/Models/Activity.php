<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    protected $table = 'activities';

    protected $primaryKey = 'act_id';

    protected $fillable = [
        'act_name',
        'act_description',
        'act_date_start',
        'act_date_end',
        'act_sea_id',
        'act_status'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'activity_x_tags', 'axt_act_id', 'axt_tag_id');
    }

    public function activitySection()
    {
        return $this->belongsTo(ActivitySection::class, 'act_sea_id', 'acs_id');
    }

}
