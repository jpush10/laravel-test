<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class EventsController extends BaseController
{
    /*
    Requirements:
    - maximum 2 sql queries
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - open the `app/Http/Controllers/EventsController` file
    - partial or not working answers also get graded so make sure you commit what you have

    Sample response on GET /events:
    ```json
    [
    {
    "id": 1,
    "name": "Laravel convention 2020",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z",
    "workshops": [
    {
    "id": 1,
    "start": "2020-02-21 10:00:00",
    "end": "2020-02-21 16:00:00",
    "event_id": 1,
    "name": "Illuminate your knowledge of the laravel code base",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z"
    }
    ]
    },
    {
    "id": 2,
    "name": "Laravel convention 2021",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z",
    "workshops": [
    {
    "id": 2,
    "start": "2021-10-21 10:00:00",
    "end": "2021-10-21 18:00:00",
    "event_id": 2,
    "name": "The new Eloquent - load more with less",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z"
    },
    {
    "id": 3,
    "start": "2021-11-21 09:00:00",
    "end": "2021-11-21 17:00:00",
    "event_id": 2,
    "name": "AutoEx - handles exceptions 100% automatic",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z"
    }
    ]
    },
    {
    "id": 3,
    "name": "React convention 2021",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z",
    "workshops": [
    {
    "id": 4,
    "start": "2021-08-21 10:00:00",
    "end": "2021-08-21 18:00:00",
    "event_id": 3,
    "name": "#NoClass pure functional programming",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z"
    },
    {
    "id": 5,
    "start": "2021-08-21 09:00:00",
    "end": "2021-08-21 17:00:00",
    "event_id": 3,
    "name": "Navigating the function jungle",
    "created_at": "2021-04-25T09:32:27.000000Z",
    "updated_at": "2021-04-25T09:32:27.000000Z"
    }
    ]
    }
    ]
     */

    public function getEventsWithWorkshops()
    {
        $event = DB::table('events as e')
            ->join('workshops as w', 'e.id', '=', 'w.event_id')
            ->select('e.*', 'w.id as w_id', 'w.start', 'w.end', 'w.event_id', 'w.name as w_name', 'w.created_at as w_created_at', 'w.updated_at as w_updated_at')
            ->get()->toArray();

        $res = [];
        foreach ($event as $key => $v) {
            $res[$v->id]['id'] = $v->id;
            $res[$v->id]['name'] = $v->name;
            $res[$v->id]['created_at'] = $v->created_at;
            $res[$v->id]['updated_at'] = $v->updated_at;
            if ($v->id === $v->event_id) {
                $res[$v->id]['workshops'][] = array(
                    'id' => $v->id,
                    'name' => $v->w_name,
                    'event_id' => $v->event_id,
                    'start' => $v->start,
                    'end' => $v->end,
                    'created_at' => $v->w_created_at,
                    'updated_at' => $v->w_updated_at,
                );
            }
        }
        $res = array_values($res);
        // echo "<pre>";
        // print_r($res);die;
        return json_encode($res);
    }

    /*
    Requirements:
    - only events that have not yet started should be included
    - the event starting time is determined by the first workshop of the event
    - the eloquent expressions should result in maximum 3 SQL queries, no matter the amount of events
    - all filtering of records should happen in the database
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - open the `app/Http/Controllers/EventsController` file
    - partial or not working answers also get graded so make sure you commit what you have
    - join, whereIn, min, groupBy, havingRaw might be helpful
    - in the sample data set  the event with id 1 is already in the past and should therefore be excluded

    Sample response on GET /futureevents:
    ```json
    [
    {
    "id": 2,
    "name": "Laravel convention 2021",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z",
    "workshops": [
    {
    "id": 2,
    "start": "2021-10-21 10:00:00",
    "end": "2021-10-21 18:00:00",
    "event_id": 2,
    "name": "The new Eloquent - load more with less",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z"
    },
    {
    "id": 3,
    "start": "2021-11-21 09:00:00",
    "end": "2021-11-21 17:00:00",
    "event_id": 2,
    "name": "AutoEx - handles exceptions 100% automatic",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z"
    }
    ]
    },
    {
    "id": 3,
    "name": "React convention 2021",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z",
    "workshops": [
    {
    "id": 4,
    "start": "2021-08-21 10:00:00",
    "end": "2021-08-21 18:00:00",
    "event_id": 3,
    "name": "#NoClass pure functional programming",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z"
    },
    {
    "id": 5,
    "start": "2021-08-21 09:00:00",
    "end": "2021-08-21 17:00:00",
    "event_id": 3,
    "name": "Navigating the function jungle",
    "created_at": "2021-04-20T07:01:14.000000Z",
    "updated_at": "2021-04-20T07:01:14.000000Z"
    }
    ]
    }
    ]
    ```
     */

    public function getFutureEventsWithWorkshops()
    {
        $current_date = Carbon::now();
        // print_r($current_date);die;
        $event = DB::table('events as e')
            ->join('workshops as w', 'e.id', '=', 'w.event_id')
            ->select('e.*', 'w.id as w_id', 'w.start', 'w.end', 'w.event_id', 'w.name as w_name', 'w.created_at as w_created_at', 'w.updated_at as w_updated_at')
            ->where('w.start', '>=', $current_date)
            ->get()->toArray();

        $res = [];
        foreach ($event as $key => $v) {
            $res[$v->id]['id'] = $v->id;
            $res[$v->id]['name'] = $v->name;
            $res[$v->id]['created_at'] = $v->created_at;
            $res[$v->id]['updated_at'] = $v->updated_at;
            if ($v->id === $v->event_id) {
                $res[$v->id]['workshops'][] = array(
                    'id' => $v->id,
                    'event_id' => $v->event_id,
                    'name' => $v->w_name,
                    'start' => $v->start,
                    'end' => $v->end,
                    'created_at' => $v->w_created_at,
                    'updated_at' => $v->w_updated_at,
                );
            }
        }
        $res = array_values($res);
        return json_encode($res);
    }
}
