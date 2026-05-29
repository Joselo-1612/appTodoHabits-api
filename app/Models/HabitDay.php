<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitDay extends Model
{
    protected $table = 'habit_days';

    protected $primaryKey = 'had_id';

    protected $fillable = [
        'had_hab_id',
        'had_day',
        'had_description',
        'had_schedule_ini',
        'had_schedule_end',
        'had_status'
    ];

    // ------------------------
    // Getters
    // ------------------------

    public function getHadId()
    {
        return $this->had_id;
    }

    public function getHadHabId()
    {
        return $this->had_hab_id;
    }

    public function getHadDay()
    {
        return $this->had_day;
    }

    public function getHadDescription()
    {
        return $this->had_description;
    }

    public function getHadScheduleIni()
    {
        return $this->had_schedule_ini;
    }

    public function getHadScheduleEnd()
    {
        return $this->had_schedule_end;
    }

    public function getHadStatus()
    {
        return $this->had_status;
    }

    // ------------------------
    // Setters
    // ------------------------

    public function setHadHabId($value)
    {
        $this->had_hab_id = $value;
        return $this;
    }

    public function setHadDay($value)
    {
        $this->had_day = $value;
        return $this;
    }

    public function setHadDescription($value)
    {
        $this->had_description = $value;
        return $this;
    }

    public function setHadScheduleIni($value)
    {
        $this->had_schedule_ini = $value;
        return $this;
    }

    public function setHadScheduleEnd($value)
    {
        $this->had_schedule_end = $value;
        return $this;
    }

    public function setHadStatus($value)
    {
        $this->had_status = $value;
        return $this;
    }

}
