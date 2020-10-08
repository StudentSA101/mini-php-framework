<?php

use Illuminate\Database\Capsule\Manager as DatabaseManager;

class Migration
{
    /**
     * instance of DatabaseManager
     *
     * @var Manager 
     */
    private $capsule;
    /**
     * Inject database manager
     *
     * @param DatabaseManager $capsule
     */
    public function __construct(DatabaseManager $capsule)
    {
        $this->capsule = $capsule;
    }
    /**
     * Create and migrate data to database
     *
     * @return void
     */
    public function migrate()
    {
        if (!$this->capsule::schema()->hasTable('mock_table')) {
            $this->capsule::schema()->create('mock_table', function ($table) {
                $table->increments('id');
                $table->string('email')->nullable();
                $table->string('title')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('tz')->nullable();
                $table->string('date')->nullable();
                $table->string('time')->nullable();
                $table->string('note')->nullable();
                $table->string('ip_address')->nullable();
                $table->tinyInteger('domain_exists');
                $table->binary('image_url')->nullable();
                $table->timestamps();
            });
        }
    }
}
