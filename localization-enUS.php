<?php

include_once "iso639.php";

class LocalizationEnUs extends LocalizationBase {
    
    function getTimestampFormat(){
        return "m-d-Y h:i:s A";
    }
    
    function getDateFormat()
    {
        return "m/d/Y";
    }
    
    function getDateFormatWithoutDay()
    {
        return "M y";
    }
    
    function getText($key){
        switch ($key){
            case "LastChangeCaption":
                return "Last change: ";
            case "LanguageTag":
                return "en";
            case "LanguageDirection":
                return "ltr"; // ltr for left to right languages; rtl for right to left
            case "LanguageLocale":
                return "en_US";
            case "ListSeparator":
                return ", ";
            case "AlternativeListSeparator":
                return " ";
            case "CaptionEnd":
                return ": ";
            case "Title":
                return "My Library";
            case "Search":
                return "Search";
            case "Filter":
                return "Filter";
            case "Clear":
                return "Reset filter";
            case "Explanation":
                return "Explanation";
            case "Read":
                return "Read";
            case "Started":
                return "Started";
            case "Unread":
                return "Unread";
            case "Borrowed":
                return "Borrowed";
            case "GivenAway":
                return "Given away";
            case "EBook":
                return "EBook";
            case "AudioBook":
                return "Audio book";
            case "ComicBook":
                return "Comic";
            case "SummaryMatch":
                return "Match in summary";
            case "DNBLink":
                return "Link to entry at <a href='https://portal.dnb.de' target='_blank'>German National Library</a>";
            case "DNBLinkSymbol":
                return "Icon for link to German National Library";
            case "OLLink":
                return "Link to entry at <a href='https://openlibrary.org' target='_blank'>OpenLibrary</a>";
            case "OLLinkSymbol":
                return "Icon for link zur OpenLibrary";
            case "ALink":
                return "Link to description at <a href='https://amazon.com' target='_blank'>Amazon</a>";
            case "ALinkSymbol":
                return "Icon for link to Amazon";
            case "ALinkInfo":
                return "Author of Android app &quot;MyLibrary&quot; may get commision when you purchae books using this link.";
            case "RestrictedBook":
                return "Hidden book";
            case "RestrictedBookSymbol":
                return "Icon for hidden book";
            case "BookCountCaption":
                return "Count of books: ";
            case "CaptionTitle":
                return "Ti&shy;tle";
            case "CaptionSeries":
                return "Se&shy;rie";
            case "CaptionVolumeNumber":
                return "No.";
            case "CaptionAuthors":
                return "Au&shy;thor(s)";
            case "CaptionAuthorFirstname":
                return "First na&shy;me";
            case "CaptionAuthorName":
                return "Na&shy;me";
            case "CaptionLocation":
                return "Lo&shy;ca&shy;tion";
            case "CaptionLanguage":
                return "Lan&shy;gua&shy;ge";
            case "CaptionInfos":
                return "In&shy;fos";
            case "CaptionType":
                return "Type";
            case "CaptionRead":
                return "Read";
            case "UnknownAuthor":
                return "unbekannt";
            case "HitInSummary":
                return "Match in summary";
            case "Restricted":
                return "Hidden";
            case "read":
                return "read";
            case "unread":
                return "unread";
            case "reading":
                return "reading";
            case "readCaption":
                return "read: ";
            case "startedCaption":
                return "started: ";
            case "-":
                return "&ndash;";
            case "(":
                return "(";
            case ")":
                return ")";
            case "OpenLibrarySearchCaption":
                return "OpenLibrary search for ISBN ";
            case "DnbSearchCaption":
                return "Search in German National Library for ISBN ";
            case "PublisherCaption":
                return "Publisher:";
            case "PublishedDate":
                return "Published: ";
            case "IsbnCaption":
                return "ISBN:";
            case "BookNumberCaption":
                return "Book number: ";
            case "PagesCaption":
                return "Page count: ";
            case "SummaryCaption":
                return "Summary: ";
            case "Summary":
                return "Summary";
            case "ErrorMissingId":
                return "Error: Missing book ID";
            case "ShelfsCaption":
                return "Shelfs: ";
            case "Shelf":
                return "Shelf";
            case "BooksInShelfCaption":
                return "Count of books in shelf: ";
            case "◄":
                return "◄";
            case "►":
                return "►";
            case "MainPage":
                return "Home";
            case "Month":
                return "Month";
            case "Started":
                return "Started";
            case "Ended":
                return "Ended";
            case "DnbCover":
                return "Cover at German National Library";
            case "OlCover":
                return "Cover at OpenLibrary";
            case "ManualCaption":
                return "Manual";
            case "Manual":
                return "Click on column caption to sort table. "
                    .  " <br/>Click on book's title for details."
                    .  " <br/>In details: "
                    .  " <br/>"
                    .  " <ul>"
                    .  " <li>Shortcut keys:"
                    .  " 	 <ul>"
                    .  " 	 <li>Esc: close details</li>"
                    .  " 	 <li>&#x2190; (Arrow left): previous book</li>"
                    .  " 	 <li>&#x2192; (Arrow right): next Buch</li>"
                    .  " 	 <li>Ctrl + ALT + D: Follow link to German National Library</li>"
                    .  " 	 <li>Ctrl + ALT + O: Follow link to OpenLibrary</li>"
                    .  " 	 <li>Ctrl + ALT + A: Follow link to Amazon</li>"
                    .  " 	 <li>Ctrl + ALT + F: Call search</li>"
                    .  " 	 <li>Ctrl + ALT + C: Show all books again</li>"
                    .  " 	 </ul>"
                    .  "  </li>"
                    .  " <li>Wiping gestures (for tablets and smart phones, e.g.): "
                    .  " 	  <ul>"
                    .  " 		 <li>left to right: previous book</li>"
                    .  " 		 <li>right to left: following book</li>"
                    .  " 	 </ul>"
                    .  "  </li>"
                    .  " </ul>";
            case "TableSortSortInfoAsc":
                return "Table is sorted by this column in ascending order";
            case "TableSortSortInfoDesc":
                return "Table is sorted by this column in descending order";
            case "TableSortSortHintAsc":
                return "Sort ascending by ";
            case "TableSortSortHintDesc":
                return "Sort descending by ";
            default:
                return "$$$" . $key . "$$$";
        }
    }
    
    function getTextWithParams($key, ...$params){
        switch ($key){
            case "ErrorBookNotFoundForId":
                $format = "Error: No book found with ID %1";
                break;
            case "CommentCaption":
                $format = "%1: ";
                break;
            case "Comment":
                $format = "%1";
                break;
            case "ReadInYear":
                $format = "Read in %1";
                break;
            case "StartEndInfo":
                $format = "Started: %1, ended: %2";
                break;
            case "CompleteYearCaption":
                $format = "Complete year %1";
                break;
            case "StatisticsForYear":
                $format = "Statistics for year %1";
                break;
            case "DnbLinkForBook":
                $format = "Link to German National Library for &quot;%1&quot;";
                break;
            case "OlLinkForBook":
                $format = "Link to OpenLibrary for &quot;%1&quot;";
                break;
            case "ALinkForBook":
                $format = "Link zu Amazon for &quot;%1&quot;";
                break;
            case "SerieVolumeAppendix":
                $format = " (Volume %1)";
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
                    return "Author: ";
                    return "Authors: ";
            case "LanguageCaption":
                if ($count == 1)
                    return "Language: ";
                    return "Languages: ";
            case "LocationCaption":
                if ($count == 1)
                    return "Location: ";
                    return "Locations: ";
            case "CategoryCaption":
                if ($count == 1)
                    return "Category: ";
                    return "Categories: ";
            case "Comments":
                if ($count <= 1)
                    return "";
                    return "Further Information: <br/>";
            case "Days":
                if ($count == 1)
                    return "Day";
                    return "Days";
            case "Matches":
                return "Matches";
            default:
                return "$#$" . $key . "$#$";
        }
    }
    
    function getTextForCountWithParams($key, $count, ...$params){
        switch ($key){
            case "MatchesForSearchString":
                if ($count == 1)
                    $format = "%1 match for &quot;%2&quot;";
                else
                    $format = "%1 matches for &quot;%2&quot;";
                    break;
            case "DaysDiff":
                if ($count == 1)
                    $format = "%1 day";
                else if ($count == 0)
                    $format = "in one day";
                else
                    $format = "%1 days";
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
                return "German";
            case "en":
                return "English";
            case "fr":
                return "French";
            case "la":
                return "Latin";
            default: // fallback for not yet translated languages: use English name of language from ISO 639 class
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
    }
    
    function getMonthNames(){
        $monate = array(1=>"January",
            2=>"February",
            3=>"March",
            4=>"April",
            5=>"May",
            6=>"June",
            7=>"July",
            8=>"August",
            9=>"September",
            10=>"October",
            11=>"November",
            12=>"December");
        return $monate;
    }
}

?>