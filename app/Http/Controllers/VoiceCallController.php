<?php

namespace App\Http\Controllers;

use App\Events\StartVoiceCall;
use Auth;
use Illuminate\Http\Request;

class VoiceCallController extends Controller
{
    public function startCall(Request $request)
    {
        $data['userToCall'] = $request->userToCall;
        $data['signalData'] = $request->signalData;
        $data['fromUser'] = Auth::user()->id;
        $data['type'] = 'incomingVoiceCall';

        broadcast(new StartVoiceCall($data))->toOthers();
        // $channel = new PrivateChannel("voice-call.user.{$data['userToCall']}");

        // return broadcast(new SignalingEvent($data))->toOthers();
    }
    public function acceptCall(Request $request)
    {
        $data['signal'] = $request->signal;
        $data['to'] = $request->toUser;
        $data['type'] = 'voiceCallAccepted';
        broadcast(new StartVoiceCall($data))->toOthers();
    }
}
