<?php

namespace App\BusinessLogic;

use Carbon\Carbon;
use App\Models\Mock;

class ParseTimeZone
{
    /**
     * A variable to hold the timezone
     *
     * @var [type]
     */
    private $timezone;
    /**
     * Inject the timezone to be used in parsing.
     *
     * @param String $timezone
     */
    public function __construct(String $timezone)
    {
        // normally inject for SOLID but static facade used for ease
        $this->timezone = $timezone;
    }
    /**
     * Parse date and time in terms of time zone
     *
     * @return array
     */
    public function parse()
    {
        $resultArray = [];
        foreach (Mock::where('tz', $this->timezone)->get()->toArray() as $key => $record) {
            $record['time'] = Carbon::parse($record['time'])->setTimezone($this->timezone)->format('Y-m-d H:i:s');
            $resultArray[$key] = $record;
        }
        return $resultArray;
    }
}
