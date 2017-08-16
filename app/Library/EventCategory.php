<?php

namespace jnanagni\Library;

use jnanagni\Library\Constants;
use jnanagni\Library\EventStore;

class EventCategory {
    protected $title = null, $id = null;
    public static $instances;

    public function __construct($title, $id) {
        $this->title = $title;
        $this->id = $id;
    }

    public function getID() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getEventList() { return EventStore::getEventList($this->id); }

    private static function create($idx, $title, $id) {
        self::$instances[$idx] = new EventCategory($title, $id);
    }

    public static function init() {
        if (isset(self::$instances)) return;

        self::$instances = array();
        // Create pre-defined objects
        self::create(0, 'Technical Events', Constants::ID_TECHNICAL);
        self::create(1, 'Non-Technical Events', Constants::ID_NON_TECHNICAL);
        self::create(2, 'Game On', Constants::ID_SPORTS);
        self::create(3, 'Fun Events', Constants::ID_FUN);
        self::create(4, 'Cultural Events', Constants::ID_CULTURAL);
        self::create(5, 'Mega Events', Constants::ID_WORKSHOP);
    }
}
