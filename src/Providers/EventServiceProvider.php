<?php

namespace Lazarus\Laravel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lazarus\Laravel\HorizonHelper;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('*', function ($eventName, array $data) {
            foreach ($data as $key => $value) {
                // We only want to log the Event data.  Currently the
                // entire app data is included in this payload.
                try {
                    if (is_object($value) && Str::is('Illuminate\*\Events\*', get_class($value))) {

                        // special handling for Horizon Complete Job Events
                        if (Str::is('Laravel\Horizon\Events\JobDeleted', get_class($value))) {
                            $horizonHelper = new HorizonHelper();
                            $value->horizonInformation = $horizonHelper->buildJobPayload($value);
                        }

                        // add event name to log object
                        $value->eventName = get_class($value);

                        // write to log
                        Storage::append(
                            '/logs/lazarus/current.log',
                            json_encode($value)
                        );
                    }
                } catch (\Exception $exception) {
                    // nothing for now
                }
            }
        });
    }
}
