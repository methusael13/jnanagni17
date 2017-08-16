<?php

namespace jnanagni\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use jnanagni\User;
use jnanagni\Registrations;
use jnanagni\Http\Requests;
use jnanagni\Library\EventStore;
use jnanagni\Library\Utility\StringUtility;

class EventController extends Controller {

    const API_ROUTE_EVT_REGISTER = 'evt-register';
    const API_ROUTE_EVT_UNREGISTER = 'evt-unregister';
    const API_ROUTE_EVT_REGSTATUS = 'evt-regstatus';
    const API_ROUTE_EVT_REGLIST = 'evt-reglist';
    const REG_DISABLED = true;

    const REG_DISABLED_MSG = 'Sorry, registration services are closed.';

    public function __construct() {
        $this->middleware('verify.csrf', ['except' => ['apiRoute']]);
    }
    
    public function getStory(Request $request) {
        if (!$request->ajax())
            return;

        $returnString = null;
        $id = $request->input('id');
        $file_path = 'storage/event-story/' . $id . '/story.cms';

        return $this->getFileOrFallback($file_path);
    }

    public function getImages(Request $request) {
        if (!$request->ajax())
            return;

        $catID = $request->input('id');
        $evtList = EventStore::getEventList($catID);
        if (!$evtList) return;

        $evtListCnt = count($evtList);
        $returnObj = array();

        for ($i = 0; $i < $evtListCnt; $i++) {
            $evtID = 'ec-' . $catID . '-' . $i;
            $returnObj[$evtID] = 'res/images/events/' . $catID . '-' . $i . '.jpg';
        }

        return response()->json($returnObj);
    }

    private function getFileOrFallback($path, $parse = false) {
        if (file_exists($path)) {
            $text = nl2br(file_get_contents($path));
            return $parse ? StringUtility::parseTags($text) : $text;
        } else {
            return nl2br(file_get_contents('storage/fallback.cms'));
        }
    }

    /**
     * returns $regmode = {
     *    0: Not logged in
     *    1: Logged in but not yet registered
     *    2: Already registered
     * } */
    public static function getRegMode(Request $request, $evtID) {
        $regmode = 0;
        if (Auth::check()) {
            $user = $request->user();
            try {
                $reg = Registrations::where('user_id', $user->id)
                                    ->where('event_id', $evtID)->firstOrFail();
                $regmode = 2;
            } catch (ModelNotFoundException $ex) { $regmode = 1; }
        }

        return $regmode;
    }

    /**
     * returns $regmode = {
     *    0: Not registered on the site
     *    1: Not yet registered for event
     *    2: Already registered for event
     * } */
    public static function apiRegMode($email, $evtID) {
        $regmode = 0;
        $user = self::getRegisteredUser($email);
        // User is registered
        if ($user !== null) {
            try {
                $reg = Registrations::where('user_id', $user->id)
                                    ->where('event_id', $evtID)->firstOrFail();
                $regmode = 2;
            } catch (ModelNotFoundException $ex) { $regmode = 1; }
        }

        return $regmode;
    }

    protected static function getRegisteredUser($email) {
        $user = null;
        try {
            $user = User::where('email', $email)
                        ->where('active', true)->firstOrFail();
        } catch (ModelNotFoundException $ex) { return null; }

        return $user;
    }

    protected static function getRegisteredList($email) {
        $user = self::getRegisteredUser($email);
        if ($user === null)
            return RegistrationController::buildJSONResponse(false, 'User not registered');

        $events = null;
        try {
            $events = Registrations::where('user_id', $user->id)->get();
        } catch (ModelNotFoundException $ex) { $events = null; }

        if ($events === null)
            return RegistrationController::buildJSONResponse(false, 'No registered events');

        $list = [];
        foreach ($events as $event) {
            $list[] = [ $event->event_id, $event->event_name ];
        }

        return RegistrationController::buildJSONResponse(true, '' , ['list' => $list]);
    }

    protected static function apiRequestHasRequirements(Request $request, $apiTag) {
        switch ($apiTag) {
            case EventController::API_ROUTE_EVT_REGISTER:
            case EventController::API_ROUTE_EVT_UNREGISTER:
            case EventController::API_ROUTE_EVT_REGSTATUS:
                return $request->has('email') && $request->has('id');
                break;
            
            case EventController::API_ROUTE_EVT_REGLIST:
                return $request->has('email');
                break;

            default:
                return true;
        }
    }

    protected static function isValidEvent($evtID) {
        $parts = explode('-', $evtID);
        $evtList = EventStore::getEventList($parts[0]);
        $evtIdx = intval($parts[1]);

        return ($evtList !== null && $evtIdx >= 0 && $evtIdx < count($evtList));
    }

    private function createRegistration($user, $evtID) {
        $parts = explode('-', $evtID);
        $evtList = EventStore::getEventList($parts[0]);
        $evtIdx = intval($parts[1]);

        Registrations::create([
            'user_id' => $user->id,
            'event_id' => $evtID,
            'event_name' => $evtList[$evtIdx]->getTitle()
        ]);
    }

    public function getInfo(Request $request) {
        if (!$request->ajax())
            return;

        $catID = $request->input('catID');
        $evtID = $request->input('evtID');
        $evtIdx = intval(explode('-', $evtID)[1]);

        $evtList = EventStore::getEventList($catID);
        if (!$evtList || $evtIdx >= count($evtList))
            return;

        $evt = $evtList[$evtIdx];
        $regmode = self::getRegMode($request, $evtID);

        $returnObj = array(
            'title'=> $evt->getTitle(),
            'idx' => (($evtIdx < 10 ? '0' : '') . $evtIdx),
            'desc' => $this->getFileOrFallback('storage/event-desc/' . $evtID . '/desc.cms', true),
            'judg' => $this->getFileOrFallback('storage/event-desc/' . $evtID . '/judg.cms', true),
            'time' => $this->getFileOrFallback('storage/event-desc/' . $evtID . '/time.cms', true),
            'cont' => $this->getFileOrFallback('storage/event-desc/' . $evtID . '/orgz.cms', true),
            'img' => 'res/images/patterns/skulls-dark.png',
            'regstatus' => $regmode,
            'int-idx' => $evtIdx,
            'length' => count($evtList)
        );

        return response()->json($returnObj);
    }

    public function apiRoute(Request $request) {
        if (!RegistrationController::authenticateAPIRoute($request))
            return RegistrationController::buildJSONResponse(false, 'Unauthorized access');

        if (!self::apiRequestHasRequirements($request, $request->input('tag')))
            return RegistrationController::buildJSONResponse(false, 'Missing data');

        switch ($request->input('tag')) {
            case EventController::API_ROUTE_EVT_REGISTER:
                return $this->apiRouteRegister($request,
                    $request->input('email'), $request->input('id'));
                break;

            case EventController::API_ROUTE_EVT_UNREGISTER:
                return $this->apiRouteUnregister($request,
                    $request->input('email'), $request->input('id'));
                break;

            case EventController::API_ROUTE_EVT_REGSTATUS:
                $regmode = self::apiRegMode($request->input('email'), $request->input('id'));
                return RegistrationController::buildJSONResponse(
                    true, '', ['regstatus' => $regmode]);
                break;

            case EventController::API_ROUTE_EVT_REGLIST:
                return self::getRegisteredList($request->input('email'));
                break;

            default:
                return RegistrationController::buildJSONResponse(false, 'Invalid Tag');
                break;
        }
    }

    protected function apiRouteRegister(Request $request, $email, $id) {
        if (EventController::REG_DISABLED)
            return RegistrationController::buildJSONResponse(false, EventController::REG_DISABLED_MSG);

        if (!self::isValidEvent($id))
            return RegistrationController::buildJSONResponse(false, 'Invalid Event ID');

        $regmode = self::apiRegMode($email, $id);
        if ($regmode === 1) {
            $user = self::getRegisteredUser($email);
            $this->createRegistration($user, $id);
            $regmode = 2;
        }

        return RegistrationController::buildJSONResponse(true, '', ['regstatus' => $regmode]);
    }

    protected function apiRouteUnregister(Request $request, $email, $id) {
        if (EventController::REG_DISABLED)
            return RegistrationController::buildJSONResponse(false, EventController::REG_DISABLED_MSG);

        if (!self::isValidEvent($id))
            return RegistrationController::buildJSONResponse(false, 'Invalid Event ID');

        $regmode = self::apiRegMode($email, $id);
        if ($regmode === 2) {
            $user = self::getRegisteredUser($email);
            Registrations::where('user_id', $user->id)
                         ->where('event_id', $id)->delete();
            $regmode = 1;
        }

        return RegistrationController::buildJSONResponse(true, '', ['regstatus' => $regmode]);
    }

    public function webRouteRegister(Request $request) {
        return $this->registerForEvent($request, $request->input('id'));
    }

    public function webRouteUnregister(Request $request) {
        return $this->unregisterEvent($request, $request->input('id'));
    }

    protected function registerForEvent(Request $request, $evtID) {
        if (EventController::REG_DISABLED)
            return RegistrationController::buildJSONResponse(false, EventController::REG_DISABLED_MSG);

        if (!self::isValidEvent($evtID)) {
            return RegistrationController::buildJSONResponse(
                false, 'Invalid Event ID', ['regstatus' => Auth::check() ? 1 : 0]);
        }

        $regmode = self::getRegMode($request, $evtID);
        // Logged in but not yet registered
        if ($regmode == 1) {
            $this->createRegistration($request->user(), $evtID);
            $regmode = 2;
        }

        return RegistrationController::buildJSONResponse(true, '', ['regstatus' => $regmode]);
    }

    protected function unregisterEvent(Request $request, $evtID) {
        if (EventController::REG_DISABLED)
            return RegistrationController::buildJSONResponse(false, EventController::REG_DISABLED_MSG);

        if (!self::isValidEvent($evtID)) {
            return RegistrationController::buildJSONResponse(
                false, 'Invalid Event ID', ['regstatus' => Auth::check() ? 1 : 0]);
        }

        $regmode = self::getRegMode($request, $evtID);
        // Registered
        if ($regmode == 2) {
            Registrations::where('user_id', $request->user()->id)
                         ->where('event_id', $evtID)->delete();
            $regmode = 1;
        }

        return RegistrationController::buildJSONResponse(true, '', ['regstatus' => $regmode]);
    }
}
