<?php

class NGErrorHandler
{
    function __construct()
    {

    }

    function throwError($area, $params = array(), Exception $e = null)
    {
        // SQL error handler
        if ($area == 'SQL') {
            $mode = NGEngine::getInstance()->getConfigParam('sql_error_show', 0);
            $currentUser = NGEngine::getInstance()->getCurrentUser();


            if (($mode == 2) ||
                (($mode == 1) && (is_object($currentUser))) ||
                (($mode == 0) && (is_object($currentUser)) && ($currentUser->isAdmin()))
            ) {
                print   "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'>".
                        "<span style='color: red;'>MySQL ERROR [" . $params['type'] . "]: " . $params['query'] . "</span><br/>".
                        "<span style=\"font: 9px arial;\">(" . $params['errNo'] . '): ' . $params['errMsg'] . '</span></div>';
            } else {
                print   "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'>".
                        "<span style='color: red;'>MySQL ERROR [" . $params['type'] . "]: *** (you don't have a permission to see this error) ***</span></span></div>";
            }
        } else {
            throw new Exception("NGErrorHandler: Unknown exception raised for area [".$area."]".(is_object($e)?' ('.$e->getCode().'): ['.$e->getMessage().']':''));
        }
    }
}
