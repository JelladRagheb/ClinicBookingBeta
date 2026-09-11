<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lexx\ChatMessenger\Models\Message;
use Lexx\ChatMessenger\Models\Participant;
use Lexx\ChatMessenger\Models\Thread;
use App\Events\MessageSent;

class MessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // Only threads for the current user, ordered by latest message
        $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        $users = User::where('id', '!=', Auth::id())
            ->with('roles')
            ->get();

        return view('messages.index', compact('threads', 'users'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->route('messages')->with('error_message', 'The thread with ID: ' . $id . ' was not found.');
        }

        // Mark as read
        $thread->markAsRead(Auth::id());
        $thread->touch(); // Update timestamp to bring to top

        $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();
        $messages = $thread->messages()->oldest()->get();
        $users = User::where('id', '!=', Auth::id())
            ->with('roles')
            ->get();

        return view('messages.index', compact('thread', 'threads', 'messages', 'users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->with('roles')
            ->get();

        return view('messages.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        // Validation for 1-on-1
        $recipientId = $input['recipient_id'];
        
        // Check if 1-on-1 thread already exists
        $existingThread = Thread::between([Auth::id(), $recipientId])->first();

        if ($existingThread) {
            // Just add the message to the existing thread
            $message = Message::create([
                'thread_id' => $existingThread->id,
                'user_id' => Auth::id(),
                'body' => $input['message'],
            ]);
            
            // Update thread timestamp for sorting
            $existingThread->touch();
            
            broadcast(new MessageSent($input['message'], $existingThread->id, Auth::user()->full_name, Auth::id(), $recipientId, $message->id));

            return redirect()->route('messages.show', $existingThread->id);
        }

        // Create new Thread
        $thread = Thread::create([
            'subject' => 'Message from ' . Auth::user()->full_name,
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        $thread->touch(); // Bring to top

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon(),
        ]);

        $thread->addParticipant($recipientId);

        broadcast(new MessageSent($input['message'], $thread->id, Auth::user()->full_name, Auth::id(), $recipientId, $message->id));

        return redirect()->route('messages.show', $thread->id);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->route('messages')->with('error_message', 'The thread with ID: ' . $id . ' was not found.');
        }

        $thread->activateAllParticipants();

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->input('message'),
        ]);

        // Update thread timestamp for sorting
        $thread->touch();

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon();
        $participant->save();

        // Find recipient (the other participant in 1-on-1)
        $recipient = $thread->participants()->where('user_id', '!=', Auth::id())->first();
        $recipientId = $recipient ? $recipient->user_id : null;

        broadcast(new MessageSent($request->input('message'), $thread->id, Auth::user()->full_name, Auth::id(), $recipientId, $message->id));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'Message sent', 'message' => $message]);
        }

        return redirect()->route('messages.show', $id);
    }
}
