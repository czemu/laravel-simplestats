<?php

namespace Czemu\Simplestats;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;
use Czemu\Simplestats\Models\Counter;
use Czemu\Simplestats\Models\Counter\Day;

class Simplestats
{
    /**
    * @var \Illuminate\Http\Request
    */
    protected $request;

    /**
    * @var \Illuminate\Cookie\CookieJar
    */
    protected $cookie;

    /**
    * Create a new Simplestats instance.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Illuminate\Cookie\CookieJar  $cookie
    * @return void
    */
    public function __construct(Request $request, CookieJar $cookie)
    {
       $this->request = $request;
       $this->cookie = $cookie;
    }

    /**
    * Gets statistics for an item.
    *
    * @param    string  $name
    * @param    int     $item_id
    * @param    mixed   $date       one day (YYYY-MM-DD) or date range (['YYYY-MM-DD', 'YYYY-MM-DD'])
    * @return   int
    */
    public function get($name, $item_id, $date = NULL)
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
    public function update($name, $item_id, $unique = FALSE)
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

        $cookie_name = $name.'_'.$item_id;

        if ( ! $unique OR ($unique AND ! $this->request->hasCookie($cookie_name)))
        {
            $day->sum += 1;
            $day->save();

            if ($unique)
            {
                $cookie = $this->cookie->queue($cookie_name, '1', config('simplestats.unique'));
            }
        }

        return [
            'total' => $counter->sum,
            'today' => $day->sum
        ];
    }
}
