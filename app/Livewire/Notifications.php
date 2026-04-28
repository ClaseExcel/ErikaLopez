<?php

namespace App\Livewire;

use Illuminate\Notifications\Notification;
use Livewire\Component;
use PhpParser\Node\Expr\FuncCall;

class Notifications extends Component
{
    public $count = 3;

    public function getListeners()
    {
        return [
            "echo-notification:App.Models.User." . auth()->user()->id . ',MessageSent' => 'render',
        ];
    }

    public function getNotificationsProperty()
    {
        return Auth()->user()->notifications->take($this->count);
    }

    public function readNotification($id)
    {
        auth()->user()->notifications->find($id)->markAsRead();
    }

    public function resetNotification()
    {
        auth()->user()->notification = 0;
        auth()->user()->save();
    }

    public function incrementCount()
    {
        $this->count += 3;
    }


    public function render()
    {
        return view('livewire.notifications');
    }
}
