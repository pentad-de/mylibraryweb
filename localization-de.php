<?php 

include_once "localization-base.php";

class LocalizationDe extends LocalizationBase{
    
    function getTimestampFormat(){
        return "d.m.Y H:i:s";
    }
    function getDateFormat()
    {
        return "d.m.Y";
    }
    
    function getDateFormatWithoutDay()
    {
        return "m/Y";
    }
    
    function getText($key){
        switch ($key){
            case "LastChangeCaption":
                return "Letzte &Auml;nderung: ";
            case "LanguageTag":
                return "de";
            case "LanguageDirection":
                return "ltr";
            case "LanguageLocale":
                return "de_DE";
            case "ListSeparator":
                return ", ";
            case "AlternativeListSeparator":
                return " ";
            case "CaptionEnd":
                return ": ";
            case "Title":
                return "Meine Bibliothek";  
            case "Search":
                return "Suche";
            case "Filter":
                return "Filtern";
            case "Clear":
                return "Filter zur&uuml;cksetzen";
            case "Explanation":
                return "Legende";
            case "Read":
                return "Gelesen";
            case "Started":
                return "Gestartet";
            case "Unread":
                return "Ungelesen";
            case "Borrowed":
                return "Ausgeliehen";
            case "GivenAway":
                return "Abgegeben";
            case "EBook":
                return "EBook";
            case "AudioBook":
                return "H&ouml;rbuch";
            case "ComicBook":
                return "Comic";
            case "SummaryMatch":
                return "&Uuml;bereinstimmung in Zusammenfassung";
            case "DNBLink":
                return "Link zum Eintrag bei der <a href='https://portal.dnb.de' target='_blank'>Deutschen Nationalbibliothek</a>";
            case "DNBLinkSymbol":
                return "Symbol f&uuml;r Link zur Deutschen Nationalbibliothek";
            case "OLLink":
                return "Link zum Eintrag bei der <a href='https://openlibrary.org' target='_blank'>OpenLibrary</a>";
            case "OLLinkSymbol":
                return "Symbol f&uuml;r Link zur OpenLibrary";
            case "ALink":
                return "Link zur Buchbeschreibung bei <a href='https://amazon.com' target='_blank'>Amazon</a>";
            case "ALinkSymbol":
                return "Symbol f&uuml;r Link zu Amazon";
            case "ALinkInfo":
                return "Der Autor der Android-App &quot;Meine Bibliothek&quot; bekommt m&ouml;glicherweise Provisionen, wenn man dort "
					  . "&uuml;ber den Link ein Buch kauft.";
            case "RestrictedBook":
                return "Verborgenes Buch";
            case "RestrictedBookSymbol":
                return "Symbol f&uuml;r verborgenes Buch";
            case "BookCountCaption":
                return "Anzahl B&uuml;cher: ";
            case "CaptionTitle":
                return "Ti&shy;tel";
            case "CaptionSeries":
                return "Rei&shy;he";
            case "CaptionVolumeNumber":
                return "Nr.";
            case "CaptionAuthors":
                return "Au&shy;to&shy;r(en)";
            case "CaptionAuthorFirstname":
                return "Vor&shy;na&shy;me";
            case "CaptionAuthorName":
                return "Na&shy;me";
            case "CaptionLocation":
                return "Stand&shy;ort";
            case "CaptionLanguage":
                return "Spra&shy;che";
            case "CaptionInfos":
                return "In&shy;fos";
            case "CaptionType":
                return "Typ";
            case "CaptionRead":
                return "Ge&shy;le&shy;sen";
            case "UnknownAuthor":
                return "unbekannt";
            case "HitInSummary":
                return "&Uuml;bereinstimmung in Zusammenfassung";
            case "Restricted":
                return "Verborgen";
            case "read":
                return "gelesen";
            case "unread":
                return "ungelesen";
            case "reading":
                return "lesend";
            case "readCaption":
                return "gelesen: ";
            case "startedCaption":
                return "begonnen: ";
            case "-":
                return "&ndash;";
            case "(":
                return "(";
            case ")":
                return ")";
            case "OpenLibrarySearchCaption":
                return "OpenLibrary-Suche f&uuml;r ISBN ";
            case "DnbSearchCaption":
                return "Suche in deutscher Nationalbibliothek f&uuml;r ISBN ";
            case "PublisherCaption":
                return "Verlag:";
            case "PublishedDate":
                return "Ver&ouml;ffentlicht: ";
            case "IsbnCaption":
                return "ISBN:";
            case "BookNumberCaption":
                return "Buch-Nummer: ";
            case "PagesCaption":
                return "Anzahl Seiten: ";
            case "SummaryCaption":
                return "Zusammenfassung: ";
            case "Summary":
                return "Zusammenfassung";
            case "ErrorMissingId":
                return "Fehler: Es wurde keine Buch-ID angegeben";
            case "ShelfsCaption":
                return "Regale: ";
            case "Shelf":
                return "Regal";
            case "BooksInShelfCaption":
                return "Anzahl B&uuml;cher im Regal: ";
            case "◄":
                return "◄";
            case "►":
                return "►";
            case "MainPage":
                return "Hauptseite";
            case "Month":
                return "Monat";
            case "Started":
                return "Begonnen";
            case "Ended":
                return "Beendet";
            case "DnbCover":
                return "Cover bei der Deutschen Nationalbibliothek";
            case "OlCover":
                return "Cover bei der OpenLibrary";
            case "ManualCaption":
                return "Anleitung";
            case "Manual":
                return "Ein Klick auf die Spalten&uuml;berschrift sortiert die Tabelle. "
				 .  " <br/>Klicke auf den Titel eines Buchs für Details." 
				 .  " <br/>In den Details: "
				 .  " <br/>"
				 .  " <ul>"
				 .  " <li>Tastenk&uuml;rzel:"
				 .  " 	 <ul>"
				 .  " 	 <li>Esc: verlassen der Details</li>"
				 .  " 	 <li>&#x2190; (Pfeil links): vorheriges Buch</li>"
				 .  " 	 <li>&#x2192; (Pfeil rechts): folgendes Buch</li>"
				 .  " 	 <li>Strg + ALT + D: Link zur Deutschen Nationalbibliothek folgen</li>"
				 .  " 	 <li>Strg + ALT + O: Link zur OpenLibrary folgen</li>"
				 .  " 	 <li>Strg + ALT + A: Link zu Amazon folgen</li>"
				 .  " 	 <li>Strg + ALT + F: Suchfeld aufrufen</li>"
				 .  " 	 <li>Strg + ALT + C: Alle B&uuml;cher wieder anzeigen</li>"
				 .  " 	 </ul>"
				 .  "  </li>"
				 .  " <li>Wischgesten (auf dem Tablet): "
				 .  " 	  <ul>"
				 .  " 		 <li>von links nach rechts: vorheriges Buch</li>"
				 .  " 		 <li>von rechts nach links: folgendes Buch</li>"
				 .  " 	 </ul>"
				 .  "  </li>"
				 .  " </ul>";
            case "TableSortSortInfoAsc":
                return "Tabelle ist aufsteigend nach dieser Spalte sortiert";
            case "TableSortSortInfoDesc":
                return "Tabelle ist absteigend nach dieser Spalte sortiert";
            case "TableSortSortHintAsc":
                return "Sortiere aufsteigend nach ";
            case "TableSortSortHintDesc":
                return "Sortiere absteigend nach ";
            default:
                return "$$$" . $key . "$$$";
        }
    }
    
    function getTextWithParams($key, ...$params){
        switch ($key){
            case "ErrorBookNotFoundForId":
                $format = "Fehler: Es wurde kein Buch mit ID %1 gefunden";
                break;
            case "CommentCaption":
                $format = "%1: ";
                break;
            case "Comment":
                $format = "%1";
                break;
            case "ReadInYear":
                $format = "Gelesen im Jahr %1";
                break;
            case "StartEndInfo":
                $format = "Begonnen: %1, beendet: %2";
                break;
            case "CompleteYearCaption":
                $format = "Ganzes Jahr %1";
                break;
            case "StatisticsForYear":
                $format = "Statistik für Jahr %1";
                break;
            case "DnbLinkForBook":
                $format = "Link zur Deutschen Nationalbibliothek f&uuml;r &quot;%1&quot;";
                break;
            case "OlLinkForBook":
                $format = "Link zur OpenLibrary f&uuml;r &quot;%1&quot;";
                break;
            case "ALinkForBook":
                $format = "Link zu Amazon f&uuml;r &quot;%1&quot;";
                break;
            case "SerieVolumeAppendix":
                $format = " (Band %1)";
                break;
            default:
                $format = "\$f$" . $key . "\$f$";
                break;               
        }
        $result = $format;
        $index = 0;
        foreach ($params as $param) {
            $index++;
            $result = str_replace("%" . $index, $param, $result);
        }  
        return $result;
    }
    
    function getTextForCount($key, $count){
        switch ($key){
            case "AuthorCaption":
                if ($count == 1)
                    return "Autor: ";
                return "Autoren: ";
            case "LanguageCaption":
                if ($count == 1)
                    return "Sprache: ";
                return "Sprachen: ";
            case "LocationCaption":
                if ($count == 1)
                    return "Standort: ";
                return "Standorte: ";
            case "CategoryCaption":
                if ($count == 1)
                    return "Kategorie: ";
                return "Kategorien: ";
            case "LocationCaption":
                if ($count == 1)
                    return "Standort: ";
                return "Standorte: ";
            case "Comments":
                if ($count <= 1)
                    return "";
                return "Weitere Informationen: <br/>";
            case "Days":
                if ($count == 1)
                    return "Tag";
                return "Tage";
            case "Matches":
                return "Treffer";
            default:
                return "$#$" . $key . "$#$";
        }
    }
    
    function getTextForCountWithParams($key, $count, ...$params){
        switch ($key){
            case "MatchesForSearchString":
                if ($count == 1)
                    $format = "%1 &Uuml;bereinstimmung f&uuml;r &quot;%2&quot;";
                else
                    $format = "%1 &Uuml;bereinstimmungen f&uuml;r &quot;%2&quot;";
                break;
            case "DaysDiff":
                if ($count == 1)
                    $format = "%1 Tag";
                else if ($count == 0)
                    $format = "in einem Tag";
                else
                    $format = "%1 Tage";
                break;
            default:
                $format = "$#F$" . $key . "\$F#$";
        }
        $result = $format;
        $index = 0;
        foreach ($params as $param) {
            $index++;
            $result = str_replace("%" . $index, $param, $result);
        }
        return $result;
    }
    
    function getLanguageByTag($languageTag){
        switch($languageTag){
            case "de":
                return "deutsch";
            case "en":
                return "englisch";
            case "fr":
                return "franz&ouml;sisch";
            case "la":
                return "lateinisch";
            default: // fallback for not yet translated languages: use native name of language
                $iso = new ISO639();
                $language = '';
                if (strlen($languageTag) == 2) // ISO639-1
                    $language = $iso->nativeByCode1($languageTag); // use languageByCode1 to use English language names
                else if (strlen($languageTag) == 3)// ISO639-3
                    $language = $iso->nativeByCode3($languageTag); // use languageByCode3 to use English language names
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
    }
    
    function getMonthNames(){
        $monate = array(1=>"Januar",
            2=>"Februar",
            3=>"M&auml;rz",
            4=>"April",
            5=>"Mai",
            6=>"Juni",
            7=>"Juli",
            8=>"August",
            9=>"September",
            10=>"Oktober",
            11=>"November",
            12=>"Dezember");
        return $monate;
    }
}

?>