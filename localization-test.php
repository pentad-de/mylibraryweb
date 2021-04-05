<?php 

include_once "localization-base.php";
/*
 * pseudo-localization;
 * used for checking whether all tests are translatable/localizable
 * */
class LocalizationTest extends LocalizationBase {
    
    function getTimestampFormat(){
        return "#\T#Y-m-d H:i:s#\T#";
    }
    function getDateFormat()
    {
        return "#\D#Y-m-d#\D#";
    }
    
    function getDateFormatWithoutDay()
    {
        return "#\M#m/Y#\M#";
    }
    
    function getMonthNames(){
        $monate = array(1=>"\$M\$Jan\$M$",
            2=>"\$M\$Feb\$M$",
            3=>"\$M\$Mar\$M$",
            4=>"\$M\$Apr\$M$",
            5=>"\$M\$May\$M$",
            6=>"\$M\$Jun\$M$",
            7=>"\$M\$Jul\$M$",
            8=>"\$M\$Aug\$M$",
            9=>"\$M\$Sep\$M$",
            10=>"\$M\$Oct\$M$",
            11=>"\$M\$Nov\$M$",
            12=>"\$M\$Dec\$M$");
        return $monate;
    }
}
?>