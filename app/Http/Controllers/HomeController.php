<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatSent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Fetch Message from database
     *
     * @return void
     */
    public function fetchMessage()
    {
        return Chat::with('user')->get();
    }

    /**
     * Send message from axios
     *
     * @param Request $request
     * @return void
     */
    public function sendMessage(Request $request)
    {
        $message = $request->user()->chats()->create([
            'message' => $request->message
        ]);

        broadcast(new ChatSent($message->load('user')))->toOthers();

        return ['status' => 'success'];
    }
}
