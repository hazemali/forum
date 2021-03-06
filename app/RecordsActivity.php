<?php


namespace laravel;


trait RecordsActivity
{


    public static function bootRecordsActivity()
    {

        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {

            static::$event(function ($model) use ($event) {

                $model->recordActivity($event);
            });

        }


        static::deleting(function($model){

            $model->activity()->delete();

        });

    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    public function activity()
    {
        return $this->morphMany('laravel\Activity', 'subject');
    }


    static function getActivitiesToRecord()
    {
        return ['created'];

    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

}