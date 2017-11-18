<?php
/**
 * Created by PhpStorm.
 * User: bobbyborisov
 * Date: 11/17/17
 * Time: 10:01 PM
 */

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->user() == null) return;

        foreach(static::getActivitiesToRecord() as $activity)
        {
            //dd($activity);
            static::$activity(function($model){
                Activity::create([
                    'user_id'    => auth()->user()->id,
                    'type'       => 'created_'.strtolower((new \ReflectionClass($model))->getShortName()),
                    'subject_id' => $model->id,
                    'subject_type' => get_class($model)
                ]);
            });
        }
    }

    public static function getActivitiesToRecord()
    {
        return ['created'];
    }
}