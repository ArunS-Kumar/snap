<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{GetLatestEventRequest};
use App\Services\{EventService};
use App\Responses\EventResponse;

class EventController extends Controller
{
    public $eventService = null;
    public $eventResponse = null;

    public function __construct()
    {
        $this->eventService = new EventService;
        $this->eventResponse = new EventResponse;   
    }


    public function getLatestEvents(GetLatestEventRequest $req)
    {
        $eventType = $req->event_type;
        $page = $req->page;
        $per_page = $req->per_page;
        $userId = $req->partner_id;
        $events = $this->eventService->getEvents($userId, $eventType, $page, $per_page);

        return $this->eventResponse->latestEvents($events);
    }
}
