<?php 

include_once "localization.php";

class DateTimeUtil{
    
    
    const DateFormatInDb = 'd/m/Y';
    
static public function timeDiffInDays($firstTime,$lastTime)
{
  
    if (is_null($firstTime) || $firstTime === '')
        return "";
        
    $firstTime = DateTime::createFromFormat(Self::DateFormatInDb, $firstTime);
 
    if (is_null($lastTime) || $lastTime === '')
        $lastTime = date(Self::DateFormatInDb);
    
        $lastTime=DateTime::createFromFormat(Self::DateFormatInDb, $lastTime);
    
    
    $timeDiff=$lastTime->getTimestamp() - $firstTime->getTimestamp();
    $dayDiff = (int) round( $timeDiff / (24 * 60 * 60) );
    return Localization::getTextForCountWithParams("DaysDiff", $dayDiff, $dayDiff);
}

static public function makeSortable($dateString)
{
    if (is_null($dateString) || $dateString === '')
        return "";
        $split = explode("/", $dateString);
        $splitLen = count($split);
        $result = $split[count($split) - 1];
        if ($splitLen > 1)
            $result = $result .  $split[count($split) - 2];
            if ($splitLen > 2)
                $result = $result .  $split[count($split) - 3];
                return $result;
}

static public function revertDate($dateString)
{
    $intVal = intval($dateString, 10);
    return 100000000 - $intVal;
}

static public function longDate($dateString)
{
    $myDateTime = DateTime::createFromFormat(Self::DateFormatInDb, $dateString);
 
    $newDateString = $myDateTime->format(Localization::getDateFormat());
    return $newDateString;
}


static public function tryLongDate($dateString)
{
    $myDateTime = DateTime::createFromFormat(Self::DateFormatInDb, $dateString);
    if ($myDateTime == false)
        return $dateString;
    
    $newDateString = $myDateTime->format(Localization::getDateFormat());
    return $newDateString;
}
static public function shortDate($date)
{
    $myDateTime = DateTime::createFromFormat(Self::DateFormatInDb, $date);
    $newDateString = $myDateTime->format(Localization::getDateFormatWithoutDay());
    return $newDateString;
}

static public function isLeapYear($year) {
    return (date('L', mktime(0, 0, 0, 1, 1, $year))==1);
} 


/**
 * @param $tDay Timestamp des Tages, der auf einen Feiertag überprüft werden soll
 * @return array(
 * 	'text' => '' Text
 *  'feast' => boolean True wenn Feiertag
 *  'arState' => '' Leerer Array oder Array mit Bundesländern für die der Feiertag gilt
 * )
 *
 * 	BW = Baden-Württemberg
 * 	BY = Bayern
 * 	BE = Berlin
 * 	BB = Brandenburg
 * 	HB = Bremen
 * 	HH = Hamburg
 * 	HE = Hessen
 * 	MV = Mecklenburg-Vorpommern
 * 	NI = Niedersachsen
 * 	NW = Nordrhein-Westfalen
 * 	RP = Rheinland-Pfalz
 * 	SL = Saarland
 * 	SN = Sachsen
 * 	ST = Sachsen-Anhalt
 * 	SH = Schleswig-Holstein
 * 	TH = Thüringen
 * 
 *  TODO: add holidays for other regions outside Germany; 
 *  TODO: add localization for holiday names; region names may stay unlocalized if only used in one country with one official language
 *  TODO: change region codes to complete ISO 3166-2 country code (like "DE-BW" instead of "BW" to prepare additional regions)
 *  TODO: all "global" holidays must be change to array of all 16 German Länder (states)
 *  */
public function getFeastInfo($tDay) {
    
    $md = date('md', $tDay);
    
    $tEaster = easter_date(date('Y', $tDay));
    
    $text = ''; $feast = ''; $arState = [];
    $half = false;
    if ($md == '0101') { $feast = _('Neujahr'); }
    else if ($md == '0501') { $feast = _('Erster Mai'); }
    else if ($md == '0106') { $feast = _('Heilige Drei Könige'); $arState = ['BW', 'BY', 'ST']; }
    else if ($md == date('md', strtotime('-2 day', $tEaster))) { $feast = _('Karfreitag'); }
    else if ($md == date('md', $tEaster)) { $feast = _('Ostersonntag'); }
    else if ($md == date('md', strtotime('+1 day', $tEaster))) { $feast = _('Ostermontag'); $arState = ['SN']; }
    else if ($md == date('md', strtotime('+39 day', $tEaster))) { $feast = _('Christi Himmelfahrt'); }
    else if ($md == date('md', strtotime('+49 day', $tEaster))) { $feast = _('Pfingstsonntag'); }
    else if ($md == date('md', strtotime('+50 day', $tEaster))) { $feast = _('Pfingstmontag'); }
    else if ($md == date('md', strtotime('+60 day', $tEaster))) { $feast = _('Fronleichnam'); $arState = ['BW', 'BY', 'HE', 'NW', 'RP', 'SL', 'SN', 'TH']; }
    else if ($md == '0815') { $feast = _('Maria Himmelfahrt'); $arState = ['SL', 'BY']; }
    else if ($md == '1003') { $feast = _('Tag der deutschen Einheit'); }
    else if ($md == '1031') { $feast = _('Reformationstag'); $arState = ['BB', 'MV', 'SN', 'ST', 'TH']; }
    else if ($md == '1101') { $feast = _('Allerheiligen'); $arState = ['BW', 'BY', 'NW', 'RP', 'SL']; }
    else if ($md == date('md', strtotime("last wednesday", mktime(0, 0, 0, 11, 23,date('Y', $tDay))))) { $feast = _('Buß- und Bettag'); $arState = ['SN']; }
    else if ($md == '1224') { $feast = _('Heiliger Abend');  $half = true;}
    else if ($md == '1225') { $feast = _('1. Weihnachtsfeiertag'); }
    else if ($md == '1226') { $feast = _('2. Weihnachtsfeiertag'); }
    else if ($md == '1231') { $feast = _('Silvester'); $half = true; }
    else if ($md == '0308') { $feast = _('Internationaler Frauentag'); $arState = ['BE']; }
    
    if (date('N', $tDay) === '6') $text = 'Samstag';
    else if (date('N', $tDay) === '7') $text = 'Sonntag';
    else $text = '';
    $arStateShort = array();
    if (sizeof($arState) > 0) {
        
        $arStateMapping = [
            'BB' => 'Brandenburg', 'BW' => 'Baden-Württemberg', 'BY' => 'Bayern', 'BE' => 'Berlin', 'HB' => 'Bremen', 'HH' => 'Hamburg', 'HE' => 'Hessen' ,'MV' => 'Mecklenburg-Vorpommern', 'NI' => 'Niedersachsen', 'NW' => 'Nordrhein-Westfalen',
            'RP' => 'Rheinland-Pfalz', 'SL' => 'Saarland', 'SN' => 'Sachsen', 'ST' => 'Sachsen-Anhalt', 'SH' => 'Schleswig-Holstein', 'TH' => 'Thüringen'
        ];
        
        foreach ($arState as $k => $v) {
            $arStateShort[$k] = $v;
            $arState[$k] = $arStateMapping[$v];
            
        }
        
    }
    
    if ($feast === '' && $text === '') $text = 'Arbeitstag';
    else if ($feast !== '' && $text !== '') $text = $text.' ('.$feast.rtrim(', '.implode(', ', $arState), ' ,').')';
    else if ($feast !== '') $text = $feast.rtrim(', '.implode(', ', $arState), ' ,');
    
    return [
        'text' => $text,
        'feast' => ($feast !== ''),
        'arState' => $arState,
        'arStateShort' => $arStateShort,
        'feastName' => $feast,
        'half' =>  $half
    ];
    
}

public static function getMonthLength($year){
    $monthLengths = array(1=>31,
        2=>28,
        3=>31,
        4=>30,
        5=>31,
        6=>30,
        7=>31,
        8=>31,
        9=>30,
        10=>31,
        11=>30,
        12=>31);
    if (self::isLeapYear($year))
        $monthLengths[2] = 29;
    return $monthLengths;
}

}

?>