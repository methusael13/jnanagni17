<?php

namespace jnanagni\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use jnanagni\User;
use jnanagni\Registrations;
use jnanagni\Http\Requests;
use jnanagni\Library\EventCategory;
use jnanagni\Library\EventStore;
use jnanagni\Library\Constants;

class PagesController extends Controller {
    const REG_ORG_PASS = '@jnj17-org-pass';

    public function __construct() { $this->middleware('verify.csrf'); }
    
    // About Page
    public function about() { return view('about'); }
    
    // Welcome Page (Desktop Only)
    public function welcome(Request $request) {
        EventCategory::init();
        $loggedin = Auth::check();
        $user = $loggedin ? $request->user() : null;

        return view('welcome', [
            'evtcats' => EventCategory::$instances,
            'evtStoryPath' => 'storage/event-story/',
            'evtDescPath' => 'storage/event-desc/',
            'contacts' => Constants::CONTACTS_LIST,
            'ftContacts' => Constants::FACULTY_CONTACTS_LIST,
            'developers' => Constants::DEVELOPERS,
            'sponsors' => [
                'count' => Constants::SPONSOR_CNT,
                'path' => Constants::SPONSOR_IMG_PATH
            ],
            'schedule' => Constants::SCHEDULE,
            'social' => [
                'titles' => Constants::SOCIAL_TITLES,
                'images' => Constants::SOCIAL_IMGS,
                'details' => Constants::SOCIAL_DTL_PATHS
            ],
            'loggedin' => $loggedin,
            'fname' => ($loggedin ? explode(' ', $user->first_name)[0] : ''),
            'lname' => ($loggedin ? $user->last_name : ''),
            'email' => ($loggedin ? $user->email_name : '')
        ]);
    }

    // Mobile Pages
    public function mobile() { return view('m.welcome'); }
    public function mobileAboutUs() { return view('m.about-us'); }
    public function mobileContacts() {
        return view('m.contact-us', [
            'contacts' => Constants::CONTACTS_LIST
        ]);
    }
    public function mobileSponsors() {
        return view('m.sponsors', [
            'sponsors' => [
                'count' => Constants::SPONSOR_CNT,
                'path' => Constants::SPONSOR_IMG_PATH
            ]
        ]);
    }

    // Contruct Page (Coming soon page)
    public function construct() { return view('construct'); }

    public function registrations() { return view('reg.landing'); }

    public function regDetails(Request $request) {
        if (!$request->has('org-pass'))
            return redirect('/registrations')->withErrors(['Organiser pass required']);

        $orgPass = $request->input('org-pass');
        if ($orgPass != PagesController::REG_ORG_PASS) {
            return redirect('/registrations')->withErrors(['Invalid organiser pass']);
        }

        EventCategory::init();
        return view('reg.details', [
            'evtcats' => EventCategory::$instances
        ]);
    }

    public function getEvtList(Request $request) {
        if (!$request->has('id'))
            return RegistrationController::buildJSONResponse(false, '');

        $id = $request->input('id');
        $events = EventStore::getEventList($id);
        $list = [];

        foreach ($events as $event)
            $list[] = strtoupper($event->getTitle());

        return RegistrationController::buildJSONResponse(true, '', [
            'list' => $list
        ]);
    }

    public function getRegList(Request $request) {
        if (!$request->has('catID') || !$request->has('evtIdx'))
            return RegistrationController::buildJSONResponse(false, '');

        $evtID = $request->input('catID') . '-' . $request->input('evtIdx');
        $regs = null;
        try {
            $regs = Registrations::where('event_id', $evtID)->get();
        } catch (ModelNotFoundException $ex) { $regs = null; }

        if ($regs === null || count($regs) === 0) {
            return RegistrationController::buildJSONResponse(
                true, '', ['count' => 0]
            );
        }

        $userids = [];
        foreach ($regs as $reg) { $userids[] = $reg->user_id; }

        $users = null;
        try {
            $users = User::find($userids);
        } catch (ModelNotFoundException $ex) { $users = null; /* Shouldn't execute */ }

        $userData = [];
        foreach ($users as $user) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at, 'UTC')
                    ->setTimezone('Asia/Kolkata');

            $userData[] = [
                'name' => implode(' ', [$user->first_name, $user->last_name]),
                'phone' => $user->phone,
                'email' => $user->email,
                'college' => $user->college,
                'time' => $date->toDateTimeString()
            ];
        }

        if ($request->input('sort')) {
            // Sort data by name
            foreach ($userData as $key => $row) { $fname[$key] = $row['name']; }
            array_multisort($fname, SORT_ASC, $userData);
        }

        return RegistrationController::buildJSONResponse(true, '', [
            'count' => count($users), 'data' => $userData
        ]);
    }
}
