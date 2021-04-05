<!DOCTYPE html>
<?php
include_once "database.php";
include_once "localization.php";
include_once "summary.php";

if (isset($_GET["debug"])) {
    error_reporting(- 1);
    ini_set('display_errors', 'On');
}

set_time_limit(60);
setlocale(LC_ALL, Localization::getText("LanguageLocale"));

if (isset($_GET["shelf"]))
    $shelf = $_GET["shelf"];
else
    $shelf = '';

$showHiddenBooks = Config::getShowHiddenBooks();
$prefix = Config::getPrefix();
?>
<html lang="<?php echo Localization::getText("LanguageTag"); ?>" dir="<?php echo Localization::getText("LanguageDirection"); ?>">
<head>
<title><?php echo Localization::getText("Title"); ?></title>
 <link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="mylibrary.css">
<script src="tablesort.js"></script>
<script src="mylibrary.js"></script>
<script>

  function KeyHandler (evt) {		
	  	
		switch (event.key) {
    		case "ArrowLeft":
    			HandlePrevious();
    			break;
    		case "ArrowRight":
    			HandleNext();
    			break;			
			case "Tab":
				if (event.shiftKey)
					ClickOnElement("PREV");
				else
					ClickOnElement("NEXT");
				break;		
			case "ArrowUp":
		  		ScrollElement(document.getElementById('main'), -10);
		  		break;
		  	case "ArrowDown":
		  		ScrollElement(document.getElementById('main'), 10);
		  		break;
		  	case "PageUp":
		  		ScrollElement(document.getElementById('main'), -25);
		  		break;
		  	case "PageDown":
		  		ScrollElement(document.getElementById('main'), 25);
		  		break;
		  	case "Home":
			  	ScrollTop(document.getElementById('main'));
			  	break;
		  	case "End":
		  		ScrollEnd(document.getElementById('main'));
			  	break;
			case "Escape":
				HandleEscape();
				break;
		}

}
  	document.addEventListener('keydown',  KeyHandler);


  
  </script>
  <style type="text/css">
  
figure {
  padding: 1px;
  float: left;
  border: 2px solid #cccccc;
  display: table;
  width: 1px;
   text-align: center;
   height: 256px;
   margin: 4px;
 
}

figure img {
   display: table-row;
    vertical-align: bottom;
    margin: auto;
    padding: 0px;
     text-align: center;
}

figure figcaption {
  padding: 2px;
  color: #000000;
  display: table-row;
    vertical-align: top;
      font-size: 50%;
   height: 39px;
}
  
  </style>
</head>
<body>
<div id="Navigation" class="sidenav">

 <?php
    echo '<h2 style="color:white; padding-left:4px;">' . Localization::getText("ShelfsCaption") . " </h2>";
    $database = new MyLibraryDatabase($showHiddenBooks);
    $shelfs = $database->getShelfs();
    
    foreach ($shelfs as $key => $shelfEntry){
        echo '<a href="?' . $prefix . 'shelf=' . $key . '">' . htmlspecialchars($key) . '</a> ';
    }
    
 
?>
	<p><a href="mylibrary.php?<?php echo $prefix; ?>" ><?php echo Localization::getText("MainPage"); ?></a></p>
</div>
<div id="main" class="main" >
<?php 
if ($shelf !== "")
{
    $books = $database->getBooksByShelf($shelf);
    
    
    echo '<p style="font-weight: 900;  font-size: large;">';
    echo '<span style="font-size: 200%;">' . Localization::getText("Title");   
    echo ' </span>';
    
    if (isset($shelfs["$shelf"]["PREV"]))
        echo '<a id="PREV" href="?'.  $prefix .  'shelf=' . $shelfs["$shelf"]["PREV"]. '">' . htmlspecialchars($shelfs["$shelf"]["PREV"]) 
             .  Localization::getText("◄") . '</a> ';
    echo "<span style=\"font-size: 200%;\" >" . Localization::getText("Shelf") . " &quot;" . htmlspecialchars($shelf) . "&quot;</span>";
    if (isset($shelfs["$shelf"]["NEXT"]))
        echo '<a id="NEXT" href="?'.  $prefix .  'shelf=' . $shelfs["$shelf"]["NEXT"] . '">'  
            .  Localization::getText("►") . htmlspecialchars($shelfs["$shelf"]["NEXT"]) . '</a></p>';
    
    echo "<div><span class='caption'>" . Localization::getText("BooksInShelfCaption") . '</span>' . count($books) . "</div>\n";
   
    foreach ($books as $book){
        $id = $book['ID'];
        $title = $book["TITLE"];
        $imagefile = $book["IMAGEPATH"];
        
        echo "<figure> \n";
        
        echo '<img width="128px" height="191px" onclick="ShowSummaryPopup(document.getElementById(\'SummaryPopup' . $id . '\'));" '
        . ' title="' . htmlspecialchars($title) . '" '
        . 'src="' . $imagefile. '" />' . "\n";
        
        
        echo "<figcaption>" . htmlspecialchars($title) . "</figcaption>". "\n";
            
        echo "</figure>\n";
        echo "\n". '<DIV id="SummaryPopup' . $id . '" style="display: none; '
        . 'position: fixed; width: 100%; height: 100%; z-index: 2; top:0; left: 0; right: 0; bottom: 0; margin: 0px; '
        . 'background-color: WhiteSmoke; cursor: pointer; overflow-y: auto; border-color: #2F2F2F; border-width: 3px; border-style: solid;" '
        . 'onclick ="CloseAllPopups(); event.stopPropagation();" '
        . 'ontouchstart = "OnTouchStart(event);" '
        . 'ontouchend = "OnTouchEnd(event);" '
        . 'onmousedown = "OnMouseDown(event);" '
        . 'onmouseup = "OnMouseUp(event);" '
            . '>'		;
            BookSummary::PrintSummary($book, "", true);
            echo "</div>\n"; 
            
    }
}

?>
</div>

 </body>
 </html>