<?php
include_once "config.php";
include_once "datetime.php";
include_once "isbn.php";

class MyLibraryDatabase
{

    var $libraryFile;

    var $lastModifiedDatetime;

    var $db;

    var $showHiddenBooks;

    function __construct($showHiddenBooks)
    {
        $this->libraryFile = Config::getLibrary();

        $lastModifiedTimestamp = filemtime($this->libraryFile);
        $this->lastModifiedDatetime = date(Localization::getTimestampFormat(), $lastModifiedTimestamp);

        $this->db = new SQLite3($this->libraryFile);
        $this->db->enableExceptions(true);
        $this->showHiddenBooks = $showHiddenBooks;
    }
    
    function getLastModifiedDatetime()
    {
        return $this->lastModifiedDatetime;
    }

    
    function fill_other_fields(&$row){
        
        $row["LANGUAGES"] = array();
        $row["LANGUAGETAGS"] = array();
        $row["LOCATIONS"] = array();
        
        $this->FillAuthorFields($row);       
        
        $this->FillCategoryFields($row);
        
        $this->FillCommentFields($row);
        
        if (isset($row["PUBLISHED_DATE"]) && $row["PUBLISHED_DATE"] !== '')
            $row["PUBLISHED_DATE_FORMATTED"] = DateTimeUtil::tryLongDate($row["PUBLISHED_DATE"]);
        
        $row["LANGUAGETAG"] = Config::getDefaultLanguageTagForBooks();
        if (count($row["LANGUAGETAGS"]) > 0)
            $row["LANGUAGETAG"] = $row["LANGUAGETAGS"][0];
            
        $imagefile = basename($row["COVER_PATH"]);
        $imagePath = Config::getCoverImageDirectory() . $imagefile;
        if ($row["ISCOMIC"] === 1)
            $imagePath = Config::getCoverImageDirectoryComic() . $imagefile;
        
        $row["IMAGEPATH"] = $imagePath;
        
        $order = "";
        $isbn = strval(str_replace('-', '', $row['ISBN']));
        if (strlen($isbn) == 10 || strlen($isbn) == 13)
            $order =  '1_' . $isbn . '_';
        else if ($isbn !== '' && !is_null($isbn))
            $order = '2_' . $isbn . '_' . htmlspecialchars($row['PUBLISHER']);
        else
            $order =  '3_' . htmlspecialchars($row['TITLE']) ;
            
        $isbn13 = $isbn;
        if (strlen($isbn) >= 10 &&  strlen($isbn) < 13)
        {
            $isbn13 = isbn::isbn10_to_13($isbn);
        }
        $row["HASISBN"] = false;
        if (strlen($isbn) == 10 || strlen($isbn) == 13)
        {
            $row["HASISBN"] = true;
        }
        
        $row["ISBN13"] = $isbn13;
        $row["ORDER"] = $order;     
        
        $this->FillReadFields($row);        
    }
    
    private function FillCommentFields(&$row)
    {
        $commentsArray = json_decode($row['COMMENTS'], true);
        
        $commentsList = array();
        $commentsDictionary = array();
        $commentsMultiDictionary = array();
        foreach ($commentsArray as $key => $value)
        {
            $commentTitle = $value['title'];
            $commentContent = $value['content'];
            
            if ($commentTitle === '')
            {
                // ignore empty title
            }
            else if ($commentTitle === Config::getLanguageCommentTitle())
            {
                $this->FillLanguage($row, $commentContent);
            }
            else if ($commentTitle === Config::getLocationCommentTitle()
                ||  $commentTitle === Config::getLentToCommentTitle())
            {
                array_push($row["LOCATIONS"] , $commentTitle);
            }
            else
            {
                array_push($commentsList, '<b>'. htmlentities($commentTitle) . '</b>: ' . htmlentities($commentContent));
                $commentsDictionary[$commentTitle] = $commentContent;
                if (!array_key_exists($commentTitle, $commentsMultiDictionary))
                    $commentsMultiDictionary[$commentTitle] = array();
                array_push($commentsMultiDictionary[$commentTitle], $commentContent);
            }
        }
        asort($commentsList);
        
        $row["COMMENTLIST"] = $commentsList;
        $row["COMMENTDICTIONARY"] = $commentsDictionary;
        $row["COMMENTDICTIONARYMULTI"] = $commentsMultiDictionary;
    }
    
    private function FillLanguage(&$row, $value)
    {
        array_push($row["LANGUAGES"] , $value);
        $languageTag = Config::getLanguageTag($value);
        array_push($row["LANGUAGETAGS"] , $languageTag);
    }
    
    private function FillCategoryFields(&$row)
    {
        $categoriesArray = json_decode($row['CATEGORIES'], true);
        $customCategories = array();
   
        $ishidden = false;
        $isborrowed = false;
        $isgivenaway = false;
        
        foreach ($categoriesArray as $key => $category)
        {
            if ($category === '')
            {
                // don't use empty category; make sure that empty string can be used for not-used special categories below
            }
            else if (Config::isLocation($category) ||  $category === Config::getEBookCategory())
            {
                array_push($row["LOCATIONS"] , $category);
            }
            else if (Config::isLanguage($category))
            {
                $this->FillLanguage($row, $category);              
            }
            else if ($category === Config::getHiddenCategory())
            {
                $ishidden = true;
            }
            else if ($category === Config::getBorrowedCategory()){
                $isborrowed = true;
            }
            else if ($category === Config::getGivenAwayCategory()){
                $isgivenaway = true;
            }
            else if ($category !== Config::getAudioBookCategory())
            {
                array_push($customCategories, $category);
            }
        } 
    
        $row["CUSTOMCATEGORIES"] = $customCategories;
        $row["ISRESTRICTED"] = $ishidden;
        $row["ISBORROWED"] = $isborrowed;
        $row["ISGIVENAWAY"] = $isgivenaway;
        
        $bookTypeName = '';
        $bookType = 'a';
        if ($row["ISCOMIC"] === 1)
        {
            $bookType = 'b';
            $bookTypeName = Localization::getText("ComicBook");
        }
        if (strpos($row['CATEGORIES'], Config::getEBookCategory()) > 0)
        {
            $bookType = 'c';
            $bookTypeName = Localization::getText("EBook");
        }
        if (strpos($row['CATEGORIES'], Config::getAudioBookCategory()) > 0)
        {
            $bookType = 'd';
            $bookTypeName = Localization::getText("AudioBook");
        }
       
        $row["XY"] = Config::getAudioBookCategory() . "->" . strpos($row['CATEGORIES'], Config::getAudioBookCategory());
        
        $row["BOOKTYPE"] = $bookType;
        $row["BOOKTYPENAME"] = $bookTypeName;
    }

    private function FillAuthorFields(&$row)
    {
        $authorNames = array();
        $authorName = '';
        if ($row['FIRSTNAME'] !== 'N' && $row['FIRSTNAME'] !== 'N')
        {
            
            if ($row['FIRSTNAME'] !== '' && !is_null($row['FIRSTNAME']))
            {
                $authorName = ''. $row['FIRSTNAME'] . ' ' ;
            }
            
            $authorName = $authorName . $row['LASTNAME'];
        }
        array_push($authorNames, $authorName);
        
        $otherAuthors = explode(',', $row['OtherAuthors']);
        foreach ($otherAuthors as $otherAuthor)
        {
            if ($otherAuthor !== '')
                array_push($authorNames, $otherAuthor);
        }
        
        $row["AUTHORNAMES"] = $authorNames;
    }

    
    private function FillReadFields(&$row)
    {
        $read = "unread";
        $readsort = "A";
        $readinterval = Localization::getText("unread");
        $readinfo = "";
        $readdays = "";
        $startDateLocal = "";
        $endDateLocal = "";
        if (!is_null($row['READ'])|| !is_null($row['StartDate']) || !is_null($row['EndDate']) )
        {
            
            if ($row['READ'] === 1)
            {
                $readinterval = Localization::getText("read");
                $read = "read";
                $readsort = "C_";
                
                if (!is_null($row['StartDate']) && !is_null($row['EndDate']))
                {
                    $startDateLocal = DateTimeUtil::longDate($row['StartDate']);
                    $endDateLocal= DateTimeUtil::longDate($row['EndDate']);
                    $readdays = DateTimeUtil::timeDiffInDays($row['StartDate'], $row['EndDate']);
                    
                    $readsort = "C_" . DateTimeUtil::revertDate(DateTimeUtil::makeSortable($row['EndDate']));
                    
                    $daysInterval = DateTimeUtil::longDate($row['StartDate'])
                    . Localization::getText("-")
                    . DateTimeUtil::longDate($row['EndDate']);
                    
                    if ($row['StartDate'] === $row['EndDate'])
                        $daysInterval = DateTimeUtil::longDate($row['StartDate']);
                    
                    $readinterval = Localization::getText("readCaption") 
                            . $daysInterval
                            . ' '
                            . Localization::getText("(") 
                            . $readdays
                            . Localization::getText(")") ;
                    $readinfo = DateTimeUtil::shortDate($row['StartDate']) . '<br/>' . DateTimeUtil::shortDate($row['EndDate']);
                    
                    
                }
              
                if ($readsort === "C_")
                {
                    $readsort = "CZ";
                }
            }
            else
            {
                if (!is_null($row['StartDate']))
                {
                    $read = "reading";
                    $readsort = "B_" . DateTimeUtil::makeSortable($row['StartDate']);
                    
                    $startDateLocal = DateTimeUtil::longDate($row['StartDate']);
                    
                    $readinterval = Localization::getText("startedCaption") . DateTimeUtil::longDate($row['StartDate']);
                    $readinfo = DateTimeUtil::shortDate($row['StartDate']);
                    
                    $readdays = DateTimeUtil::timeDiffInDays($row['StartDate'], date(""));
                }
            }            
        }
        
        $row["READSTATE"] = $read;
        $row["READSORT"] = $readsort;
        $row["READINTERVAL"] = $readinterval;
        $row["READINFO"] = $readinfo;
        $row["READDAYS"] = $readdays;
        $row["READSTART"] = $startDateLocal;
        $row["READEND"] = $endDateLocal;
    }

    const BASESELECT = 'SELECT b.TITLE, a.FIRSTNAME, a.LASTNAME, b.ADDITIONAL_AUTHORS, b.ISBN, b.AMAZON_URL, ' 
        . 'b.CATEGORIES, b.COMMENTS, b.PUBLISHER, b.PAGES, b.ID, b.SUMMARY, ' 
        . "b.READ, json_extract(READING_DATES, '$[0].startDate') StartDate, json_extract(READING_DATES, '$[0].endDate') EndDate, "
        . "b.COVER_PATH, b.PUBLISHED_DATE, " 
        . 'a.FIRSTNAME || \' \' || a.LASTNAME CompleteName, ' . 'a.LASTNAME || \' \' || a.FIRSTNAME CompleteNameLastNameFirst, '
        . ' (  ( ' 
        . '         SELECT GROUP_CONCAT( ' 
        . "              IFNULL(firstname, '') || CASE IFNULL(firstname, '') WHEN '' THEN '' ELSE ' ' END || IFNULL(lastname , ''), ','"
        . '          ) FROM Author a INNER JOIN JSON_EACH(b.ADDITIONAL_AUTHORS) aa ON a.ID = aa.Value ' 
        . '      ) ) OtherAuthors, ' 
        . 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Title, \'"\', "")' . ', "«", ""), "»", ""), "ä", "ae"), "ö", "oe"), "ü", "ue"), "ß", "SS"), "Ä", "Ae"), "Ö", "Oe"), "Ü", "Ue") Sortkey, ' 
        . "json_extract(SERIES, '$[0].title') SerieTitle, json_extract(SERIES, '$[0].volume') SerieVolume, b.ISCOMIC ";
 
     const ALLFIELDS = 'ID, ADDITIONAL_AUTHORS, AMAZON_URL, AUTHOR, CATEGORIES, COMMENTS, COVER_PATH, FNAC_URL, IN_WISHLIST, ISBN, PAGES, PUBLISHED_DATE, PUBLISHER, READ, READING_DATES, SERIES, SUMMARY, TITLE';
          
     function getBookCommicUnion()
     {
        return "SELECT 0 ISCOMIC, " . self::ALLFIELDS . " FROM BOOK UNION SELECT 1 ISCOMIC, " . self::ALLFIELDS . " FROM COMIC";
     }
        
    function getFromStatementPrefix($withBorrowedAndGivenAway)
    {
        $prefix = 'FROM (' . self::getBookCommicUnion() . ') b LEFT JOIN AUTHOR a ON a.ID = b.AUTHOR ' . 'WHERE IN_WISHLIST=0 ' ;
        if (!$withBorrowedAndGivenAway)
        {
            $prefix = $prefix . 'AND b.CATEGORIES NOT LIKE \'%' . Config::getBorrowedCategory() . '%\' ';
            $prefix = $prefix . 'AND b.CATEGORIES NOT LIKE \'%' . Config::getGivenAwayCategory() . '%\' ';
        }

        if (! $this->showHiddenBooks) {
            $prefix = $prefix . ' AND  b.CATEGORIES NOT LIKE \'%' . Config::getHiddenCategory() . '%\' ';
        }
        return $prefix;
    }
    
    public function readBookInfos(string $searchString)
    {
        $statement = self::BASESELECT;
        
        if ($searchString !== '') {
            $toReplace = array(
                " ",
                "\t",
                "\n",
                ";",
                "'",
                "\"",
                ",",
                ".",
                "*",
                "?",
                "-",
                "_"
            );
            $searchStringWithReplacesBlanks = str_replace($toReplace, '%', $searchString);
            $searchStringWithReplacesBlanksForSummary = str_replace($toReplace, '_', $searchString);
            $statement = $statement . ", b.SUMMARY LIKE '%" . $searchStringWithReplacesBlanksForSummary . "%' HitInSummary ";
        }
        
        $statement  = $statement . self::getFromStatementPrefix(false);
      
        if ($searchString !== '') {

            $statement = $statement . 'AND ( ' . " b.TITLE LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR a.FIRSTNAME LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR a.LASTNAME LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR CompleteName LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR CompleteNameLastNameFirst LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR b.CATEGORIES LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR SerieTitle LIKE '%" . $searchStringWithReplacesBlanks . "%' " . " OR HitInSummary != 0" . " OR EXISTS (" . '   SELECT json_extract(value, "$.content") otherTitle' . '   FROM Json_each(b.COMMENTS) ' . "   WHERE json_extract(value, '$.title') IN ('Originaltitel', 'Alternativtitel', 'Deutscher Titel')" . "   AND otherTitle LIKE '%" . $searchStringWithReplacesBlanks . "%'" . " )" . " OR ( " . " SELECT aa.value " . " FROM json_each(b.ADDITIONAL_AUTHORS) aa " . " WHERE json_valid(b.ADDITIONAL_AUTHORS) " . " AND aa.Value IN  " . " ( " . " SELECT oaid FROM  " . " (  " . "       SELECT oa.ID oaid,  " . " 	       oa.FIRSTNAME || ' ' || oa.LASTNAME OaCompleteName, " . " 		   oa.LASTNAME || ' ' || oa.FIRSTNAME OaCompleteNameLastNameFirst " . " 	  FROM AUTHOR oa " . " 	  WHERE OaCompleteName LIKE '%" . $searchStringWithReplacesBlanks . "%'  " . " 	  OR OaCompleteNameLastNameFirst LIKE '%" . $searchStringWithReplacesBlanks . "%'  " . " ) " . " ) " . " ) " . ') ';
        }

        $statement = $statement . 'ORDER BY Sortkey COLLATE NOCASE';

        $res = $this->db->query($statement);
        $result = array();
        
        while ($row = $res->fetchArray()){
            $this->fill_other_fields($row);
            array_push($result, $row);
            
        }
        
        return $result;
    }
    
    public function readBookInfo($id)
    {
        $statement = self::BASESELECT . self::getFromStatementPrefix(true) . " AND b.ID = " . $id;
        
        $res = $this->db->query($statement);
        
        
        while ($row = $res->fetchArray()){
            self::fill_other_fields($row);            
            return $row;            
        }
        return null;
    }
    
    public function getShelfs(){
        $shelfsByCategory = $this->getShelfsByCategory();
        $shelfsByComment = $this->getShelfsByComment();
        
        $allshelfs =  array_merge($shelfsByCategory, $shelfsByComment);
        
        $shelfs = array();
        $EbookShelf = Config::getEBookCategory();
        $previousShelfs = array();
        $nextShelfs = array();
        $previousShelf = "";
        foreach ($allshelfs as $shelfValue)
        {
            if (Config::isShelf($shelfValue))
            {
                array_push($shelfs, $shelfValue);
                
                if ($previousShelf === "")
                {
                    $firstShelf = $shelfValue;
                    $previousShelfs[$firstShelf] = $EbookShelf;
                }
                else
                {
                    $previousShelfs[$shelfValue] = $previousShelf;
                    $nextShelfs[$previousShelf] = $shelfValue;
                }
                
                $previousShelf = $shelfValue;
            }
        }
        array_push($shelfs, $EbookShelf);
        
        $nextShelfs[$previousShelf] = $EbookShelf;
        $nextShelfs[$EbookShelf] = $firstShelf;
        $previousShelfs[$EbookShelf] = $previousShelf;
        
        $result = array();
        foreach ($shelfs as $shelf){
            $shelfEntry = array();
            $shelfEntry["CURR"] = $shelf;
            $shelfEntry["NEXT"] = $nextShelfs[$shelf];
            $shelfEntry["PREV"] = $previousShelfs[$shelf];
            $result[$shelf] = $shelfEntry;
        }
        
        return $result;
       
    }
    
    public function getShelfsByComment(){
        $result = array();
        $locationCommentTitle = Config::getLocationCommentTitle();
        if ($locationCommentTitle !== '')
        {
            $statementShelfsByComment = 
            "SELECT DISTINCT commentvalue FROM "
            . " ( "
            . " SELECT json_extract(json_each.value, '$.title') commenttype, json_extract(json_each.value, '$.content') commentvalue "
            . "   FROM Book b JOIN JSON_EACH(COMMENTS) "
            . "  ) WHERE commenttype= '" . $locationCommentTitle . "'";
            
            $resShelfesByComment = $this->db->query ($statementShelfsByComment);
            while ($rowShelfByComment = $resShelfesByComment->fetchArray())
            {
                $shelfValue = $rowShelfByComment["commentvalue"];
                if (Config::isShelf($shelfValue))
                {
                    array_push($result, $shelfValue);                    
                }
            }
        }
        
        return $result;
    }
    
    public function getShelfsByCategory(){
        $statementShelfes =
        'SELECT DISTINCT json_each.value shelf FROM (' . self::getBookCommicUnion() . ') b JOIN json_each(CATEGORIES)';
        
        if (!Config::getShowHiddenBooks()){
            $statementShelfes = $statementShelfes . " WHERE b.CATEGORIES NOT LIKE '%" . Config::getHiddenCategory(). "%' ";
        }
        
        $statementShelfes = $statementShelfes . ' ORDER BY shelf';
        
        $resShelfes = $this->db->query ($statementShelfes);
           
        $result = array();
     
        while ($rowShelf = $resShelfes->fetchArray())
        {
            $shelfValue = $rowShelf["shelf"];
            if (Config::isShelf($shelfValue))
            {
                array_push($result, $shelfValue);              
            }
        }     
        
        return $result;
    }
    
    public function getBooksByShelf($shelf)
    {
        $locationCommentTitle = Config::getLocationCommentTitle();
        
        $statement = 'SELECT b.Title, b.Id bookId, b.COVER_PATH  FROM (' . self::getBookCommicUnion() . ') b ' 
            . ' JOIN json_each(CATEGORIES) ' 
            . 'WHERE json_each.value LIKE :shelf  '; 
        if (! Config::getShowHiddenBooks())
            $statement = $statement . " AND b.CATEGORIES NOT LIKE '%" . Config::getHiddenCategory() .  "%' ";
        $statement = $statement . ' UNION '
            . "SELECT b.Title, b.Id bookId, b.COVER_PATH  FROM BOOK b "
            . "JOIN json_each(COMMENTS) "
            . "WHERE json_extract(json_each.value, '$.title') LIKE ':locationCommentTitle' "
            . "AND json_extract(json_each.value, '$.content') LIKE ':shelf'" ;
        if (! Config::getShowHiddenBooks())
            $statement = $statement . " AND b.CATEGORIES NOT LIKE '%" . Config::getHiddenCategory() .  "%' ";
        $statement = $statement . 'ORDER BY Title';
        
        $stmt = $this->db->prepare($statement);
        $stmt->bindValue(':shelf', $shelf, SQLITE3_TEXT);
        $stmt->bindValue(':locationCommentTitle', $locationCommentTitle, SQLITE3_TEXT);
        $res = $stmt->execute();

        $result = array();
        while ($row = $res->fetchArray()) {
            $id = $row["bookId"];
            $book = self::readBookInfo($id);
            
            array_push($result, $book);
        }
        
        return $result;
    }
  
    
    public function getBooksByReadingDate(){
        
        $statement =
        "SELECT b.Id Id, b.READ, b.CATEGORIES, b.Title Title, "
        . "json_extract(READING_DATES, '$[0].startDate') StartDate, json_extract(READING_DATES, '$[0].endDate') EndDate, "
        . "substr(json_extract(READING_DATES, '$[0].startDate'), 7, 4) Year, "
        . "substr(json_extract(READING_DATES, '$[0].startDate'), 4, 2) Month, "
        . "substr(json_extract(READING_DATES, '$[0].startDate'), 1, 2) Day, "
        . "substr(json_extract(READING_DATES, '$[0].endDate'), 7, 4) EndYear, "
        . "substr(json_extract(READING_DATES, '$[0].endDate'), 4, 2) EndMonth, "
        . "substr(json_extract(READING_DATES, '$[0].endDate'), 1, 2) EndDay "
        . ' FROM (' . self::getBookCommicUnion() . ') b '
        . " WHERE IN_WISHLIST=0 AND StartDate IS NOT NULL ";
        
        if (!Config::getShowHiddenBooks())
            $statement = $statement . " AND b.CATEGORIES NOT LIKE '%" . Config::getHiddenCategory() .  "%' ";
        
        $statement = $statement . 'ORDER BY Year, Month, Day, EndYear, EndMonth, EndDay, b.Title, b.Id';
        $res = $this->db->query ($statement);
        $result = array();
        while ($book = $res->fetchArray()) {
            $id = $book["Id"];
            $bookInfo = self::readBookInfo($id);
            $bookInfo["STARTDATE"] = $book["StartDate"];
            $bookInfo["ENDDATE"] = $book["EndDate"];
            $bookInfo["STARTYEAR"] = $book["Year"];
            $bookInfo["STARTMONTH"] = $book["Month"];
            $bookInfo["STARTDAY"] = $book["Day"];
            $bookInfo["ENDYEAR"] = $book["EndYear"];
            $bookInfo["ENDMONTH"] = $book["EndMonth"];
            $bookInfo["ENDDDAY"] = $book["EndDay"];
            array_push($result, $bookInfo);
        }
        return $result;
                                                                            
    }
   
}

?>