<?php
#[\AllowDynamicProperties]
class erLhcoreClassExtensionWildixin {

    public function __construct() {

    }

    public function run() {

    }

    public function __get($var) {
        switch ($var) {

            case 'settings' :
                $this->settings = include ('extension/wildixin/settings/settings.ini.php');
                return $this->settings;

            default :
                ;
                break;
        }
    }

}

?>