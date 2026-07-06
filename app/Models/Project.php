<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $primaryKey = 'pro_id';

    protected $fillable = [
        'pro_prg_id',
        'pro_use_id',
        'pro_name',
        'pro_description',
        'pro_priority',
        'pro_date_start',
        'pro_date_end',
        'pro_status'
    ];

    public function getProId()
    {
        return $this->pro_id;
    }

    public function setProId($pro_id)
    {
        $this->pro_id = $pro_id;
    }

    public function getProPrgId()
    {
        return $this->pro_prg_id;
    }

    public function setProPrgId($pro_prg_id)
    {
        $this->pro_prg_id = $pro_prg_id;
    }

    public function getProUseId()
    {
        return $this->pro_use_id;
    }

    public function setProUseId($pro_use_id)
    {
        $this->pro_use_id = $pro_use_id;
    }

    public function getProName()
    {
        return $this->pro_name;
    }

    public function setProName($pro_name)
    {
        $this->pro_name = $pro_name;
    }

    public function getProDescription()
    {
        return $this->pro_description;
    }

    public function setProDescription($pro_description)
    {
        $this->pro_description = $pro_description;
    }

    public function getProPriority()
    {
        return $this->pro_priority;
    }

    public function setProPriority($pro_priority)
    {
        $this->pro_priority = $pro_priority;
    }

    public function getProDateStart()
    {
        return $this->pro_date_start;
    }

    public function setProDateStart($pro_date_start)
    {
        $this->pro_date_start = $pro_date_start;
    }

    public function getProDateEnd()
    {
        return $this->pro_date_end;
    }

    public function setProDateEnd($pro_date_end)
    {
        $this->pro_date_end = $pro_date_end;
    }

    public function getProStatus()
    {
        return $this->pro_status;
    }

    public function setProStatus($pro_status)
    {
        $this->pro_status = $pro_status;
    }

    public function activitySection()
    {
        return $this->hasMany(ActivitySection::class, 'acs_pro_id', 'pro_id');
    }
}