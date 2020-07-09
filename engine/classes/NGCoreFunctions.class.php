<?php
/**
 * NGCMS Basic CORE functions class
 * 
 * @copyright 2020 Next Generation CMS (http://ngcms.ru)
 * 
 * @author Vitaly Ponomarev <vp7@mail.ru>
 * @author Megaket4up <megaket4up@gmail.com>
 * 
 */

// namespace NGCMS\Core;

class NGCoreFunctions
{
    /**
     * Check minimum php ver
     * 
     * @param $phpVersion
     *
     * @return void
     */
    static public function checkPhpVersion(string $phpVersion) : void
    {
        if (!version_compare(PHP_VERSION, $phpVersion, '>=')) {
            // throw new \Exception("The minimum required PHP version is " . $phpVersion);
            self::fatalError("The minimum required PHP version is " . $phpVersion);
        }
    }

    /**
     * Resolve required dependencies
     * 
     * @param array $list
     * 
     * @return void
     */
    static public function resolveDeps(array $list) : void
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
                static::fatalError("Cannot load file CORE libraries of <a href=\"http://ngcms.ru/\">NGCMS</a> (<b>engine/core.php</b>), PHP extension [" . $kModule . "] with function [" . $vFunction . "] is not loaded!");
            }
        }
    }
    
    /**
     * Show fatal error
     * 
     * @param string $msg
     * @todo throw exception
     *
     * @return void
     */
    static public function fatalError($msg)
    {
        print   "<html>\n<head><title>FATAL EXECUTION ERROR</title></head>\n".
                "<body>\n<div style='font: 24px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'>".
                "<span style='color: red;'>FATAL ERROR</span><br/>".
                "<span style=\"font: 16px arial;\">" . $msg . "</span>".
                "</div>\n</body>\n</html>\n";
        die();
    }
}
