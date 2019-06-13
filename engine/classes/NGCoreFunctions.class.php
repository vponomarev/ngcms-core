<?php

class NGCoreFunctions
{
    static function resolveDeps($list)
    {
        $kModule = '';
        $vFunction = '';
        foreach ($list as $pModule => $pFunction) {
            $is_error = false;
            if (is_array($pFunction)) {
                foreach ($pFunction as $kModule => $vFunction) {
                    if (extension_loaded($kModule) && (($vFunction == '') || function_exists($vFunction))) {
                        break;
                    }
                    if (!next($pFunction)) {
                        $is_error = true;
                    }
                }
            } elseif (!extension_loaded($pModule) || !(($vFunction == '') || function_exists($pFunction))) {
                $kModule = $pModule;
                $vFunction = $pFunction;
                $is_error = true;
            }

            if ($is_error) {
                print   "<html>\n<head><title>FATAL EXECUTION ERROR</title></head>\n".
                        "<body>\n<div style='font: 24px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'>".
                        "<span style='color: red;'>FATAL ERROR</span><br/>".
                        "<span style=\"font: 16px arial;\"> Cannot load file CORE libraries of <a href=\"http://ngcms.ru/\">NGCMS</a> (<b>engine/core.php</b>), PHP extension [" . $kModule . "] with function [" . $vFunction . "] is not loaded!</span>".
                        "</div>\n</body>\n</html>\n";
                die();
            }
        }

    }
}
