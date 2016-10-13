<?php

namespace App\Handlers\Fun;

use App\Slack\Event;
use App\Slack\Message;
use App\Handlers\Handler;
use App\Loader\LoadsData;
use App\Loader\JsonLoader;

final class PunHandler extends Handler
{
    use LoadsData;

    protected $dataFile = 'puns.json';

    /**
     * @param JsonLoader $loader
     */
    public function __construct(JsonLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandle(Event $event)
    {
        return 
            $event->isMessage() && 
            ($event->isDirectMessage() || $event->mentions($this->eve->userId())) &&
            $event->matches('/\b(pun)\b/i')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->loadData();

        $this->send(
            Message::saying($this->data->random())
            ->inChannel($event->channel())
            ->to($event->sender())
        );
    }
}

