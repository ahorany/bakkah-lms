<?php

namespace App\Helpers;

class ConditionHelper {

    public static function WhereCombo($eloquent, $name)
    {
        if(request()->has($name) && request()->get($name) != -1)
        {
            $eloquent = $eloquent->where($name, request()->get($name));
        }
        return $eloquent;
    }

    public static function WhereComboArray($eloquent, $array=[])
    {
        foreach($array as $name)
        {
            $eloquent = self::WhereCombo($eloquent, $name);
        }
        return $eloquent;
    }
}
