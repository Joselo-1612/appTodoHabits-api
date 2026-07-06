<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySection extends Model
{
    protected $table = 'activity_sections';
    protected $primaryKey = 'acs_id';
    protected $fillable = [
        'acs_pro_id',
        'acs_name',
        'acs_status'
    ];

    public function getAcsId()
    {
        return $this->acs_id;
    }

    public function setAcsId($acs_id)
    {
        $this->acs_id = $acs_id;
    }

    public function getAcsProId()
    {
        return $this->acs_pro_id;
    }

    public function setAcsProId($acs_pro_id)
    {
        $this->acs_pro_id = $acs_pro_id;
    }

    public function getAcsName()
    {
        return $this->acs_name;
    }

    public function setAcsName($acs_name)
    {
        $this->acs_name = $acs_name;
    }

    public function getAcsStatus()
    {
        return $this->acs_status;
    }

    public function setAcsStatus($acs_status)
    {
        $this->acs_status = $acs_status;
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'acs_pro_id', 'pro_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'act_sea_id', 'acs_id');
    }
}
