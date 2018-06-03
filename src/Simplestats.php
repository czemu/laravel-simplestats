<?php

namespace Czemu\Simplestats;

use Czemu\Simplestats\Models\Counter;
use Czemu\Simplestats\Models\Counter\Day;

class Simplestats
{
    /**
    * Gets statistics for an item.
    *
    * @param    string  $name
    * @param    int     $item_id
    * @param    mixed   $date       one day (YYYY-MM-DD) or date range (['YYYY-MM-DD', 'YYYY-MM-DD'])
    * @return   int
    */
    public static function get($name, $item_id, $date = NULL)
    {
        $counter = Counter::where('item_id', $item_id)
            ->where('name', $name)
            ->first();

        if ( ! empty($date) AND ! empty($counter))
        {
            $sum = Day::where('counter_id', $counter->id);

            if (is_array($date))
            {
                $sum->whereBetween('day', [$date[0], $date[1]]);
            }
            else
            {
                $sum->where('day', $date);
            }

            return (int)$sum->sum('sum', 0);
        }

        return ! is_null($counter) ? $counter->sum : 0;
    }

    /**
    * Updates statistics for an item.
    *
    * @param    string  $name
    * @param    int     $item_id
    * @param    bool    $unique
    * @return   array
    */
    public static function update($name, $item_id, $unique = FALSE)
    {
        $counter = Counter::firstOrNew([
            'name' => $name,
            'item_id' => $item_id
        ]);

        $counter->sum += 1;
        $counter->save();

        $day = Day::firstOrNew([
            'counter_id' => $counter->id,
            'day' => date('Y-m-d')
        ]);

        if ( ! $unique OR ($unique AND ! isset($COOKIE[$name.'_'.$item_id])))
        {
            $day->sum += 1;
            $day->save();

            if ($unique)
            {
                unset($_COOKIE[$name.'_'.$item_id]);
                setcookie($name.'_'.$item_id, true, time() + config('simplestats.unique'));
            }
        }

        return [
            'total' => $counter->sum,
            'today' => $day->sum
        ];
    }
}
