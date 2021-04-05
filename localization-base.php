<?php

include_once "iso639.php";

abstract class LocalizationBase{

    abstract function getTimestampFormat();
    
    abstract function getDateFormat();
    
    abstract function getDateFormatWithoutDay();
    
    function getText($key){       
                return "$$$" . $key . "$$$";        
    }
    
    function getTextWithParams($key, ...$params){
        
        $format = "\$f$" . $key . "\$f$";
        
        $result = $format;
        $index = 0;
        foreach ($params as $param) {
            $index++;
            $result = str_replace("%" . $index, $param, $result);
        }
        return $result;
    }
    
    function getTextForCount($key, $count){
                return "$#$" . $key . "$#$";
    }
    
    function getTextForCountWithParams($key, $count, ...$params){        
        $format = "$#F$" . $key . "\$F#$";
        
        $result = $format;
        $index = 0;
        foreach ($params as $param) {
            $index++;
            $result = str_replace("%" . $index, $param, $result);
        }
        return $result;
    }
    
    function getLanguageByTag($languageTag){
        return self::getLanguageNativeNameByTag($languageTag);        
    }
    
    private function getLanguageNativeNameByTag($languageTag){
        $iso = new ISO639();
        $language = '';
        if (strlen($languageTag) == 2) // ISO639-1
            $language = $iso->nativeByCode1($languageTag); 
        else if (strlen($languageTag) == 3)// ISO639-3
            $language = $iso->nativeByCode3($languageTag); 
        
        if ($language !== '')
        {
            $languages =  explode(',', $language);
            if (count($languages) > 0)
                $language = $languages[0];
        }
        if ($language === '')
            $language = $languageTag;
        return $language;
    }
    
    private function getLanguageEnglishNameByTag($languageTag){
        $iso = new ISO639();
        $language = '';
        if (strlen($languageTag) == 2) // ISO639-1
            $language = $iso->languageByCode1($languageTag); 
        else if (strlen($languageTag) == 3)// ISO639-3
            $language = $iso->languageByCode3($languageTag); 
            
        if ($language !== '')
        {
            $languages =  explode(',', $language);
            if (count($languages) > 0)
                $language = $languages[0];
        }
        if ($language === '')
            $language = $languageTag;
        return $language;
    }
    
    abstract function getMonthNames();
}