<?php

namespace jnanagni\Library;

use Courier\Courier;

class PushNotifier {
    private $courier;
    protected $recipients;

    public function __construct() {
        $this->recipients = [];

        // Add default recipients
        foreach (self::getDefaultList() as $num)
            $this->addRecipient($num);

        $this->courier = new Courier;
        $this->courier->setRegion('intl');
    }

    private static function getDefaultList() {
        return [
            '+917535079848'
        ];
    }

    public function addRecipient($recipient) {
        $this->recipients[$recipient] = true;
    }

    public function dispatchMessage($body) {
        foreach ($this->recipients as $recipient) {
            $this->courier->setRecipient($recipient)
                          ->setBody($body)->send();
        }
    }
}
