<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Mock;
use voku\helper\AntiXSS;
use Interop\Queue\Message;
use Interop\Queue\Context;
use Interop\Queue\Processor;
use Intervention\Image\ImageManager;

class ImportJob implements Processor
{

    /**
     * Constructor for if injection is required
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return Interop\Queue\Processor::ACK
     */
    public function process(Message $message, Context $context)
    {
        $data = json_decode($message->getBody(), true);
        // AntiXss to sanitize the horrible data in the note column
        $antiXss = new AntiXSS();
        foreach ($data as $record) {
            Mock::create([
                'id' => $record['id'],
                'title' => $record['title'],
                'first_name' => $record['first_name'],
                'last_name' => $record['last_name'],
                'email' => $record['email'],
                'tz' => $record['tz'],
                'date' => $record['date'],
                'time' => Carbon::parse($record['date'] . ' ' . $record['time'], $record['tz'])->setTimezone('Africa/Johannesburg'),
                'note' => $antiXss->xss_clean($record['note']),
                'ip_address' => gethostbyname(explode('@',  $record['email'])[1]),
                'domain_exists' => $this->domainExists($record['email']),
                'image_url' => $this->parseImage(null, $record['first_name'] . ' ' . $record['last_name'] . ' ' . $record['email'])
            ]);
        }
        return self::ACK;
    }
    /**
     * Method to check if domain exists
     *
     * @param string $email
     * @param string $record
     * @return bool
     */
    protected function domainExists($email, $record = 'MX')
    {
        if (checkdnsrr(explode("@", $email)[1], $record)) {
            return true;
        }
        return false;
    }
    /**
     * Method to parse images in terms of test
     *
     * @param string $image
     * @param string $text
     * @return void
     */
    protected function parseImage($image = null, $text)
    {
        // Idea is to store the image on a cdn and save the link in db. 
        // for test purposes saving to local
        $makeImage = (new ImageManager())->make('https://source.unsplash.com/random');
        // add text to card as per test description
        $makeImage->text(
            $text,
            $makeImage->getWidth() / 2,
            $makeImage->getHeight() / 2,
            function ($font) {
                $font->size(500);
                $font->color('#fdf6e3');
                $font->align('center');
                $font->valign('top');
                $font->angle(45);
                return $font;
            }
        );
        $uuid = uniqid('', true);
        $makeImage->save(__DIR__ . '\..\..\public\\' . $uuid . '.jpeg');
        return env('BASE_URL') . "/$uuid.jpeg";
    }
}
