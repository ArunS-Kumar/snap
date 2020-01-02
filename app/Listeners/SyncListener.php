<?php

namespace App\Listeners;

use App\Events\SyncEvent;
use App\Jobs\ProductCommunicationJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Exceptions\ActionNotAllowedException;

class SyncListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SyncEvent  $event
     * @return void
     */
    public function handle(SyncEvent $event)
    {
        $data = [];
        switch ($event->eventType) 
        {
            case 'sync_single_client_create':
                    $client = $event->data->toArray();
                    $data = [ 'client' => $client, 'type' => 'sync_single_client'];
                        try {
                            \Log::error($data);
                            ProductCommunicationJob::dispatch($data);
                        } catch (\Exception $e) {
                            \Log::error("failed SyncListener(sync_single_client)");
                            \Log::error($e->getMessage());
                        }
                break;

                default:
                    throw new ActionNotAllowedException("Invalid event type");
        }
    }
}
