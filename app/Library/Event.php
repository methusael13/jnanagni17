<?php

namespace jnanagni\Library;

class Event {
    protected $title, $story;

    public function __construct($title) { $this->setTitle($title); }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }
}
