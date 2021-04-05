<?php 

class Config
{
    /**
     * path for library file
     * @return string
     */
    static public function getLibrary()
    {
        return getcwd(). '/sample.db';
    }
    
    /**
     * category name for hidden books;
     * empty string: don't use
     * @return string
     */
    static public function getHiddenCategory()
    {
        return "Hidden";
    }
    
    /**
     * category name for books you have read, but which are not part of library,
     * e.g. because you borrowed them from somebody else, but you want to store that you have read them and at which time;
     * empty string: don't use
     * @return string
     */
    static public function getBorrowedCategory()
    {
        return "Borrowed";
    }
    
    /** 
     * category name for books that have been in your library but are not anymore;
     * e.g. you gave them to somebody else
     * empty string: don't use
     * @return string
     */
    static public function getGivenAwayCategory()
    {
        return "GivenAway";
    }
    
    /**
     * determine whether hidden books show be shown;
     * in example below you may add HTTP get parameter "hidden" with value "mysecretpassword" for showing hidden books;
     * return false: don't use
     * @return boolean
     */
    static public function getShowHiddenBooks()
    {
        if (isset($_GET["hidden"]))
        {    
            return $_GET["hidden"] === "mysecretpassword";
        }
        return false;
    }
    
    /**
     * you may call this method in getShowHiddenBooks to implement basic HTTP authentication;
     * in this sample a fixed user with fixed password is used
     * @return boolean
     */
    static private function checkUserAndPassword()
    {
        $users = array();
        array_push($users, "secretuser");
        array_push($users, "anothersecretuser");
        $data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);
        return array_key_exists($data['username'], $users);
    }
   
    /**
     * URL prefix use for calling URL, used to implement links when default getShowHiddenBooks() above is used;
     * use empty string if you use basic HTTP authentication
     * @return string
     */
    static public function getPrefix(){
        $prefix = "";
        if (self::getShowHiddenBooks())
            $prefix = "hidden=mysecretpassword&";
        return $prefix;
    }
    
    /**
     * get base directory for image files
     * @return string
     */
    static public function getImageBaseDirectory(){
        return "./Images/";
    }
    
    /**
     * get directory for image files for books
     * @return string
     */
    static public function getCoverImageDirectory(){
        return self::getImageBaseDirectory() . "Books/";
    }
    
    /**
     * get directory for image files for comics
     * @return string
     */
    static public function getCoverImageDirectoryComic(){
        return self::getImageBaseDirectory() . "Comics/";
    }
    
    /**
     * gets category used for ebooks
     * @return string
     */
    static public function getEBookCategory(){
        return "EBook";
    }
    
    /**
     * gets category used for audio books
     * @return string
     */
    static public function getAudioBookCategory(){
        return utf8_encode("AudioBook");
    }
    
    /**
     * determines whether a category is used as language;
     * return empty string, if you don't want to use comments as languages 
     * @param string $category
     * @return boolean
     */
    static public function isLanguage($category){
        return  $category === utf8_encode('Français') || $category === 'Deutsch' ||$category === 'Lingua Latina';
    }
    
    /**
     * gets comment title used as language
     * @return string
     */
    static public function getLanguageCommentTitle()
    {
        return "Language";
    }
    /**
     * get ISO639-1 language tag for language name;
     * must be set correctly for hyphenation in summary section
     * @param string $languageName
     * @return string|
     */
    static public function getLanguageTag($languageName){
        switch($languageName){           
            case utf8_encode("Français"):
                return "fr";
            case "Deutsch":
                return "de";
            case "Lingua Latina":
                return "la";
            default:
                return self::getDefaultLanguageTagForBooks();
        }
    }
    
    /**
     * gets language tag for all book without explicit language specified
     * @return string
     */
    static public function getDefaultLanguageTagForBooks(){
        return "en";
    }
    
    /** 
     * gets language used for dialogue 
     * */
    static public function getDefaultLanguageForDialogue(){
        return "en-US";
    }
    
    /**
     * determines whether a category is considered as a book shelf;
     * return false, if you do not specify shelfs
     * @param string  $category
     * @return boolean
     */
    static public function isShelf($category){
        return strlen($category) === 2; // sample: all categories of length 2 are considered to be shelves
    }
    
    /**
     * determines whether a category is considered as a location;
     * return false, if you do not specify locations
     * @param string $category
     * @return boolean
     */
    static public function isLocation($category){
        return self::isShelf($category) || $category === 'BedsideTable'; // sample: 'BedsideTable' is a location, but no shelf
    }
    
    /** 
     * comment title used for locations;
     * leave empty, if you don't use comments for locations
     * @return string
     */
    static public function getLocationCommentTitle()
    {
        return "Location";
    }
    
    /**
     * comment title used book lent to somebody else;
     * leave empty, if you don't use comments for lented books
     * @return string
     */
    static public function getLentToCommentTitle()
    {
        return "LentTo";
    }
    
    /**
     * determines whether an author is unknown; 
     * as MyLibrary app forces to enter at least a LASTNAME for author, you can use 
     * specials name for unknown authors; 
     * in sample below firstname 'N' combined with lastname 'N' is used for unknown author;
     * return false, if you don't use such kind of substitutes
     * @param string $book
     * @return boolean
     */
    static public function hasNoAuthor($book){
        return ($book["FIRSTNAME"] === 'N' && $book["LASTNAME"] === 'N')
            || ($book["FIRSTNAME"] === '' && $book["LASTNAME"] === '');
    }
    
    /**
     * region to use for holiday calender; 
     * possible values @see datetime.php
     * @return string
     */
    static public function getRegion()
    {
        return "";
    }
}

?>