<?php
include_once "config.php";
include_once "localization-de.php";
include_once "localization-enUS.php";
// add includes for other translations here
include_once "localization-test.php";


class Localization{
    
    
    private static function getInstance(){
        
        static $instance;
        if ($instance == null)
        {
            $languageTag = Config::getDefaultLanguageForDialogue();
            $instance = self::getLocalizationInstance($languageTag);
        }
        return $instance;
    }
    
    private static function getLocalizationInstance($languageTag){
        if (self::startsWith($languageTag, 'test'))
            return new LocalizationTest();
        
        if (self::startsWith($languageTag, 'de'))
            return new LocalizationDe();
        
        // add other translations here
        
        return new LocalizationEnUs(); // default
    }
    
    private static function startsWith($string, $startString)
    {
        $len = strlen($startString);
        if ($len > strlen($string))
            return false;
        
        return (substr($string, 0, $len) === $startString); 
    }
    
    static function getTimestampFormat(){
        return self::getInstance()->getTimestampFormat();
    }
    
    static function getDateFormat()
    {
        return self::getInstance()->getDateFormat();
    }
    
    static function getDateFormatWithoutDay()
    {
        return self::getInstance()->getDateFormatWithoutDay();
    }
    
    static function getText($key){
        return self::getInstance()->getText($key);
    }
    
    static function getTextWithParams($key, ...$params){
        return self::getInstance()->getTextWithParams($key, ...$params);
    }
    
    static function getTextForCount($key, $count){
        return self::getInstance()->getTextForCount($key, $count);
    }
    
    static function getTextForCountWithParams($key, $count, ...$params){
        return self::getInstance()->getTextForCountWithParams($key, $count, ...$params);
    }
    
    static function getLanguageByTag($languageTag){
        return self::getInstance()->getLanguageByTag($languageTag);
    }
    
    function getMonthNames(){
        return self::getInstance()->getMonthNames();
    }
}
?>