<?php
namespace buyMeCoffee\Controllers;

class ButtonControllers
{
    public function getButton()
    {
        return array(
                "buttonText" => 'Buy Me Coffee',
                "enableMessage" => 'yes',
                "enableName" => 'yes',
                "enableEmail" => 'yes',
                "methods" => [],
                "advanced" => array (
                    "enable" => 'yes',
                    "bgColor" => 'rgba(250, 212, 0, 1)',
                    "color" => 'rgba(0, 0, 0, 1)',
                    "minWidth" => '180',
                    "textAlign" => 'center',
                    "minHeight" => '43',
                    "fontSize" => 21,
                    "radius" => 4
                )
        );
    }
}
