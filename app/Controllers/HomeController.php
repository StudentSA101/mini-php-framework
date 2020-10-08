<?php

namespace App\Controllers;

use App\BusinessLogic\ParseTimeZone;
use App\Models\Mock;
use App\Models\Queue;

/**
 * Home controller for Request Response handling
 */
class HomeController
{

    public function __construct()
    {
        //
    }
    /**
     * The main url called
     *
     * @return string
     */
    public function index()
    {
        echo 'welcome this is the landing page.';
    }

    /**
     * Should return the timezone data as per test
     * @param string $data
     * @return string
     */
    public function timeZones($data)
    {
        parse_str($data, $query);
        if(isset($query['timezone'])){
            echo json_encode([
                'Timezone' => $query['timezone'],
                'total_contact' => Mock::where('tz', $query['timezone'])->get()->count(),
                'contacts' => Mock::where('tz', $query['timezone'])->get()->toArray()
            ]);
        } else {
            echo 'Please provide a valid query';
        }
    }

    /**
     * Should return all data as per test
     *
     * @return void
     */
    public function contact()
    {
       echo json_encode(Mock::paginate(100));
    }
    /**
     * Should return the timezone data per local Timezone as per test
     *
     * @return string
     */
    public function localDateAndTime($data)
    {
        parse_str($data, $query);
        if(isset($query['timezone'])){
            echo json_encode([
                'Timezone' => $query['timezone'],
                'total_contact' => Mock::where('tz', $query['timezone'])->get()->count(),
                'contacts' => (new ParseTimeZone($query['timezone']))->parse()
            ]);
        } else {
            echo 'Please provide a valid query';
        }
    }
    /**
     * Should return route does not exist
     *
     * @return string
     */
    public function fourOhFour()
    {
        echo 'This page does not exists';
    }
}
