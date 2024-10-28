<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;

class RpaLogBroadcast implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $log;

    public function __construct($log)
    {
        $this->log = $log;
    }

    public function broadcastOn()
    {
        return new Channel('rpa-logs');
    }

    public function broadcastAs()
    {
        return 'log.message';
    }
}
