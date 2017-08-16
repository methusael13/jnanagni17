<?php

namespace jnanagni\Library;

use jnanagni\Library\Constants;
use jnanagni\Library\Event;

class EventStore {
    public static $dataStore;

    // Create events and add to store
    private static function createEvents($id, $titles) {
        $arr = [];
        foreach ($titles as $title)
            $arr[] = new Event($title);
        self::$dataStore[$id] = $arr;
    }

    public static function populateStore() {
        if (isset(self::$dataStore))
            return;

        self::$dataStore = array();
        // Events for Technical Category
        $titles = [
            'HYDRORISER', 'CI-PHER', 'ELECTROGUISAL', 'ANNIHILATOR',
            'APPTITUDE', 'EX-GESIS', 'CONCATENATION', 'ELECTRICIO',
            'TINKERER', 'NOPC', 'INCLINO', 'CUANDIGO', 'AMELIORATOR'
        ];
        self::createEvents(Constants::ID_TECHNICAL, $titles);

        // Events for Non Technical Category
        $titles = [
            'ABHIVYAKTI', 'THIRD VISION',
            'MIST TREASURE HUNT', 'Q-COGNITO', 'FREEDOSCRAWL',
            'KALAKRITI', 'CRAFTS-VILLA', 'ENTHUSE',
            'CRICKET KEEDA'
        ];
        self::createEvents(Constants::ID_NON_TECHNICAL, $titles);

        // Events for Cultural Category
        $titles = [
            'FANCY FOOTWORK', 'SARGAM', 'KRITIKA', 'LOL', 'NAUTANKISHALA'
        ];
        self::createEvents(Constants::ID_CULTURAL, $titles);

        // Events for Sports Category
        $titles = [
            'CARROM', 'TABLE TENNIS', 'CHESS',
            'BADMINTON', 'NEED FOR SPEED', 'COUNTER STRIKE',
            'FIFA'
        ];
        self::createEvents(Constants::ID_SPORTS, $titles);

        // Events for Fun Category
        $titles = [
            'RUBIK\'S CUBE', 'MINI-MILITIA', 'BOWLING',
            'DART', 'THROWBALL'
        ];
        self::createEvents(Constants::ID_FUN, $titles);

        // Workshop events
        $titles = [
            'SAMAGAM', 'CELEBRITY VISIT', 'STARTUP FAIR',
            'ROCK SYNDROME'
        ];
        self::createEvents(Constants::ID_WORKSHOP, $titles);
    }

    public static function getEventList($catID) {
        self::populateStore();
        return self::$dataStore[$catID];
    }
}
