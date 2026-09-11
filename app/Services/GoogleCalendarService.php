<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

class GoogleCalendarService
{

    protected $client;
    protected $calendarService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.client_redirect'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->calendarService = new Calendar($this->client);
        // throw new \Exception('Not implemented');
    }

    function authenticate($code)
    {
        $this->client->fetchAccessTokenWithAuthCode($code); //old authenticate
        session(['google_access_token' => $this->client->getAccessToken()]);
    }

    function getClient()
    {
        if (session('google_access_token')) {
            $this->client->setAccessToken(session('google_access_token'));
        }
        return $this->client;
    }

    function listEvents($calendarId = 'primary')
    {
        $this->getClient();
        $events = $this->calendarService->events->listEvents($calendarId);
        return $events->getItems();
    }

    function createEvent($eventData, $calendarId = 'primary')
    {
        $this->getClient();
        $event = new Event($eventData);
        $event = $this->calendarService->events->insert($calendarId, $event);

        return $event;
    }
}
