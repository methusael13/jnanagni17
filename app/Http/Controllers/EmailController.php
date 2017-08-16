<?php

namespace jnanagni\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use jnanagni\User;
use jnanagni\Http\Requests;
use jnanagni\Mail\BulkWelcome;

class EmailController extends Controller {

    public function regVerify(Request $request, $hash, $token) {
        $regTimeOut = 24;  // 24 Hours
        $user = null; $err_redir = 404;

        // Check hash
        try {
            $user = User::where('email_hash', $hash)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return abort($err_redir);
        }

        // Check active
        if ($user->active)
            return abort($err_redir);

        // Check token
        if ($user->token !== $token)
            return abort($err_redir);

        // Check timestamp
        $tsnow = Carbon::now();
        if ($tsnow->diffInHours($user->updated_at) > $regTimeOut)
            return abort($err_redir);

        // Activate the user
        $user->active = true; $user->token = null;
        $user->save();

        // Send welcome mail
        Mail::to($user)->queue(new BulkWelcome($user, BulkWelcome::VWELCOME));

        return view('mail.verified', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
        ]);
    }

    public function sendBulkWelcomeMail(Request $request) {
        if ($request->input('ac_tag') != 'bulkMail')
            return abort(404);

        $users = null;
        try {
            $users = User::where('active', true)->get();
        } catch (ModelNotFoundException $ex) { $users = null; }

        if (!$users || count($users) === 0)
            return;

        // Queue mail in bulk
        foreach ($users as $user) {
            Mail::to($user)->queue(new BulkWelcome($user));
        }

        return 'Enqueued bulk mail';
    }
}
