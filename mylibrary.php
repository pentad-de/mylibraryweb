<!DOCTYPE html>
<?php 
include_once "database.php";
include_once "localization.php";
include_once "summary.php";

if (isset($_GET["debug"]))
{
    error_reporting(-1);
    ini_set('display_errors','On');
}

set_time_limit(60);
setlocale(LC_ALL, Localization::getText("LanguageLocale"));

if (isset($_GET["search"]))
    $searchString = $_GET["search"];
else
    $searchString = '';
$showHiddenBooks = Config::getShowHiddenBooks();
$prefix = Config::getPrefix();
?>
<html lang="<?php echo Localization::getText("LanguageTag"); ?>" dir="<?php echo Localization::getText("LanguageDirection"); ?>">
<head>
    <title><?php echo Localization::getText("Title"); ?></title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="mylibrary.css">
    <script src="tablesort.php"></script>
    <script src="mylibrary.js"></script>
    <script>
    document.addEventListener('keydown',  KeyForPopupHandler);
    </script>
</head>
 <body>
  <h1><?php echo Localization::getText("Title"); ?></h1>
  <h2><?php echo Localization::getText("Search"); ?></h2>
  <form action="?" method="GET">
	  <input name="search" id="searchfield" value= 
		 <?php			 
  				
  				echo '"';
  				if ($searchString !== '')
  					echo htmlspecialchars($searchString);
  				echo '"';
 		 ?>		 
			 />

	  <input type="submit" value="<?php echo Localization::getText("Filter"); ?>" /> 
	  <button type="button" id="clearbutton" 
	  onclick="window.location.href='mylibrary.php?<?php echo $prefix; ?>search=';">
	  <?php echo Localization::getText("Clear"); ?></button>
  </form>
  
    <details style="margin-top: 4px;">
		  <summary><span class="caption"><?php echo Localization::getText("Explanation"); ?></span></summary>

		  <table style="margin-top: 4px;">
		  	  <tr><td><img src='read.png' height='16' alt='<?php echo Localization::getText("Read"); ?>'></td><td><?php echo Localization::getText("Read"); ?></td></tr>
		  	  <tr><td><img src='reading.png' height='16' alt='<?php echo Localization::getText("Started"); ?>'></td><td><?php echo Localization::getText("Started"); ?></td></tr>
		  	  <tr><td><img src='unread.png' height='16' alt='<?php echo Localization::getText("Unread"); ?>'></td><td><?php echo Localization::getText("Unread"); ?></td></tr>
			  <tr><td><img src='ebook.png' height='16' alt='<?php echo Localization::getText("EBook"); ?>'></td><td><?php echo Localization::getText("EBook"); ?></td></tr>
			  <tr><td><img src='headphones.png' height='16' alt='<?php echo Localization::getText("AudioBook"); ?>'></td><td><?php echo Localization::getText("AudioBook"); ?></td></tr>
			  <tr><td><img src='comic.png' height='16' alt='<?php echo Localization::getText("ComicBook"); ?>'></td><td><?php echo Localization::getText("ComicBook"); ?></td></tr>
			  <tr><td><img src='found.png' height='16' alt='<?php echo Localization::getText("SummaryMatch"); ?>'></td><td><?php echo Localization::getText("SummaryMatch"); ?></td></tr>
			  <tr><td><img src='info.png' height='16' alt='<?php echo Localization::getText("DNBLinkSymbol"); ?>'></td><td><?php echo Localization::getText("DNBLink"); ?></td></tr>
			  <tr><td><img src='OpenLibrary.png' height='16' alt='<?php echo Localization::getText("OLLinkSymbol"); ?>'></td><td><?php echo Localization::getText("OLLink"); ?></td></tr>
			  <tr><td><img src='a.png' height='16' alt='<?php echo Localization::getText("ALinkSymbol"); ?>'></td><td><?php echo Localization::getText("ALink"); ?>
				  <div style="font-size: xx-small;"><?php echo Localization::getText("ALinkInfo"); ?></div></td></tr>  
			 <?php
			
			 if ($showHiddenBooks)
			 {
			     ?>
			     <tr><td><img src='restricted.png' height='16' alt='<?php echo Localization::getText("RestrictedBookSymbol"); ?>'></td><td><?php echo Localization::getText("RestrictedBook"); ?></td></tr>
		  	    <?php
			 }
				  
			  ?>
		  </table>
		  
	  </details> 	  
		  
		  
		     <main>
		     <p><a href="statistics.php?<?php echo $prefix;  ?>year=<?php echo date("Y");  ?>" target="_blank" >
		     <?php echo Localization::getTextWithParams("StatisticsForYear", date("Y"));  ?></a>
		  
		      <?php 
		 
		 $database = new MyLibraryDatabase($showHiddenBooks);
		 echo "<div><span class='caption'>" . Localization::getText("LastChangeCaption") . "</span>" . $database->getLastModifiedDatetime() . "</div>";
		 
		 $books = $database->readBookInfos($searchString);
		 
		 echo "<div><span class='caption'>" . Localization::getText("BookCountCaption")  . "</span>" . count($books) . "</div>";
		 
		 ?>
	    <table style="border-top:1px solid black; border-collapse: collapse; empty-cells:show; table-layout:fixed; 
					  word-wrap: break-word; text-wrap: normal; width:100%; " class="sortierbar">
			 <caption>
				 <details style="margin-top: 4px;">
		 		 <summary><span class="caption"><?php echo Localization::getText("ManualCaption"); ?></span></summary>
		 		 <div>
		 		 <?php echo Localization::getText("Manual"); ?>
		 		 </div>
			     </details>
			</caption>
			<colgroup>
			<col name="title" style="max-width:280px; width:42%; text-align:left; border-right-style:none">
			<col name="volumeNumber" style="max-width:20px; width:3.5%; text-align:left; border-left-style:none">
			<col name="completeName" style="max-width:220px; width:26%; text-align:left; border-right-style:none">
			<col name="lastName" style="max-width:30px; width:4%; text-align:left; border-left-style:none">
			<col name="location" style="max-width:70px; width:8%; text-align:left; ">
			<col name="info" style="max-width:170px; width:8.5%; text-align:left; border-right-style:none">
			<col name="type" style="max-width:30px; width:3%; text-align:left; border-left-style:none;">
			<col name="read" style="max-width:30px; width:3%; text-align:left; border-left-style:none;">
			</colgroup>
		 <thead>
		 	  <tr>
			  <th name="title" style="text-align:left; border-right-style:none" >
				  <div>
					  <div><?php echo Localization::getText("CaptionTitle"); ?></div>
					  <div style="text-align:right"><span class="subcaption"><?php echo Localization::getText("CaptionSeries"); ?></span></div>
				  </div></th>
			  <th name="volumeNumber" style="text-align:left; border-left-style:none" ><div>&nbsp;<br/><span class="subcaption"><?php echo Localization::getText("CaptionVolumeNumber"); ?></span></div></th>
			  <th name="completeName" style="text-align:left; border-right-style:none" ><div><?php echo Localization::getText("CaptionAuthors"); ?><br/><span class="subcaption"><?php echo Localization::getText("CaptionAuthorFirstname"); ?></span></div></th>
			  <th name="lastName" style="text-align:left; border-left-style:none" ><div>&nbsp;<br/><span class="subcaption"><?php echo Localization::getText("CaptionAuthorName"); ?></span></div></th>
			  <th name="location" style="text-align:left;" ><div><?php echo Localization::getText("CaptionLocation"); ?><br/><span class="subcaption">&nbsp;</span></div></th>
			  <th name="info" style="text-align:left; border-right-style:none" ><div><?php echo Localization::getText("CaptionInfos"); ?><br/><span class="subcaption"><?php echo Localization::getText("CaptionLanguage"); ?></span></div></th>
			  <th name="type" style="text-align:left; border-left-style:none; border-right-style:none" ><div>&nbsp;<br/><span class="subcaption"><?php echo Localization::getText("CaptionType"); ?></span></div></th>
			  <th name="read" style="text-align:left; border-left-style:none" ><div title="Gelesen" >&nbsp;<br/><span class="subcaption"><?php echo Localization::getText("CaptionRead"); ?></span></div></th>
		  </tr>
		 </thead>		 
		 <tbody>
		 
		 <?php 
		 
		
		 foreach ($books as $book)
		 {
		     $id = $book['ID'];
		     echo '<tr style="border-top:1px solid black">' . '<td name="Title' . $id. '" style="border-right-style:none" ' 
                . 'onclick ="ShowSummaryPopup(document.getElementById(\'SummaryPopup' . $id . '\'));" ' . '>' 
                . '<div style="margin-bottom:0px">' . htmlspecialchars($book['TITLE']) . '</div>';
            if (! is_null($book['SerieTitle']) && $book['SerieTitle'] !== '') {
                echo '<div style="margin-top:0px; text-align: right;"><span class="subtitle">' . htmlspecialchars($book['SerieTitle']) . '</span></div>';
            }
            echo '</td><td style="border-left-style:none; vertical-align:bottom"> ';
            if (!is_null($book['SerieVolume']) && $book['SerieVolume'] !== '')
            {
                echo '<span class="subtitle">' . htmlspecialchars($book['SerieVolume']) . '</span>';
            }
            else
            {
                echo '<div style="display: none">0</div>';
            }
            
    
            echo '</td><td style="border-right-style:none ">';
            if (Config::hasNoAuthor($book))
                echo '<span class="subtitle">' . Localization::getText("UnknownAuthor") . "</span>";
            else
                echo htmlspecialchars(implode(', ', $book["AUTHORNAMES"]));
            echo '</td><td style="border-left-style: none"><div style="display:none">';
            if (Config::hasNoAuthor($book))
                echo "Zzzzzzzzzz";
            else
                echo htmlspecialchars($book['LASTNAME']); 
            echo '</div>';
            ?>
            
            </td>
            
            <td>
            <?php 
            foreach ($book["LOCATIONS"] as $location){
                echo "<a href=shelf.php?" . $prefix . "shelf=". $location . ">" . htmlspecialchars($location) . "</a> ";
            }
            ?>
            </td>
            
         	<td style="border-right-style:none ">
         <?php 
            foreach ($book["LANGUAGETAGS"] as $languageTag)
            {
                $language = Localization::getLanguageByTag($languageTag);
                echo ' <span class="smalltext">' . $language. '</span>&nbsp;<img src="' . $languageTag . '.png" style="border-width:1px; border-style: solid; border-color:lightgray;" '
                    .' title="' . $language . '" alt="' . $language . '" width="12px"/>&nbsp;';
            }
            if (count($book["LANGUAGETAGS"]) == 0)
            {
                echo ' <span class="smalltext" style="display: none">!!!!!</span> ';
            }
            
            echo '<div style="display:none;">';
            echo $book["ORDER"];
            echo '</div>';
            
            if (!is_null($searchString) && $searchString !== '' && $book['HitInSummary'] === 1)
            {
                echo '<img src="found.png" height="16" alt="' . Localization::getText("HitInSummary") . '" '
                      .' title="' . Localization::getText("HitInSummary") . '" '
                      . 'onclick ="  '
                	      . 'ShowSummaryPopup(document.getElementById(\'SummaryPopup' . $id . '\')); '
                  				    . ' " '
  					    . '/>';
            }
            
            if ($book["ISRESTRICTED"])
            {
                echo ' <img src=\'restricted.png\' height=\'16\' alt=\'' . Localization::getText("Restricted") .  '\'> ';
            }
            
            if ($book["HASISBN"])
            {
                echo ' <a href="https://portal.dnb.de/opac.htm?referrer=Wikipedia&method=enhancedSearch&index=num&term='
    		  	      . $book["ISBN"] . '&operator=and" '
		  	      . 'target="_blank"><img title="' . Localization::getText("DnbSearchCaption") . $book["ISBN"] 
		  	      . '" src="info.png" height="16" alt="' . Localization::getText("DnbSearchCaption") . $book["ISBN"] . '" /></a>&nbsp;';
    		  	
    		  	echo '<a href="https://openlibrary.org/isbn/' . $book["ISBN"] . '" target="_blank">'
		  	      . '<img id="OLLink' . $id . '" style="display: none; " '
			      . ' title="' .  Localization::getText("OpenLibrarySearchCaption") . $book["ISBN"] . '" '
			      . ' onload="showOlbLink(this, \'' . $book["ISBN13"] . '\');"'
			      . ' src="OpenLibrary.png" height="16" alt="'. Localization::getText("OpenLibrarySearchCaption") . $book["ISBN"] . '" /></a>&nbsp;</div>'; 
    		  	      
            }
            if ($book["AMAZON_URL"] != '')
            {
                echo ' <a href="' . $book["AMAZON_URL"] . '" '
			      . 'target="_blank"><img title="' . Localization::getText("ALink")
			      . '" src="a.png" height="16" alt="' . Localization::getText("ALink") . '" /></a>&nbsp;';
            }
            
            echo '</td><td style="border-left-style:none; border-right-style:none; ">';
            
            $bookType = $book["BOOKTYPE"];
            echo ' <div style="display:none">' . $bookType . '</div>';
            
            if ($bookType == 'c')
            {
                echo ' <img title="' . Localization::getText("EBook") . '" src=\'ebook.png\' ' 
		  	      . 'alt=\'' . Localization::getText("EBook") . '\' '
		  	      . 'style="max-width:100% !important; min-width: 8px; max-height:16px !important ;height:auto;width:auto;"'
		  	      .'/> ';
            }
            if ($bookType == 'd')
            {
                echo ' <img title="' . Localization::getText("AudioBook") . '" src=\'headphones.png\' ' 
			       . 'alt=\'' . Localization::getText("AudioBook") . '\' '
		  	      . 'style="max-width:100% !important ; min-width: 8px; max-height:16px !important ;height:auto;width:auto;"'
		  	      .'/> ';
            }
            if ($bookType == 'b')
            {
                echo ' <img title="' . Localization::getText("ComicBook") . '" src=\'comic.png\' '
			         . 'alt=\'' . Localization::getText("ComicBook") . '\' '
			         . 'style="max-width:100% !important ; min-width: 8px; max-height:16px !important ;height:auto;width:auto;"'
			         .'/> ';
            }
            
            echo '</td><td style="border-left-style:none ">';
            
            echo ' <div style="display:none; font-size:xx-small">' . $book["READSORT"] . '</div>';
            echo ' <img title="' . $book["READINTERVAL"] . '" src=\'' . $book["READSTATE"] . '.png\' '
			      . 'style="max-width:100% !important ; min-width: 12px; max-height:16px !important ;height:auto;width:auto;"'
			      .'alt=\''
			      . Localization::getText($book["READSTATE"]) .'\' /> ';
	       echo '<div class="readinfo">' . $book["READINFO"] . '</div>';
	       
	       echo "\n". '<DIV id="SummaryPopup' . $id . '" style="display: none; '
		 		     . 'position: fixed; width: 100%; height: 100%; z-index: 2; top:0; left: 0; right: 0; bottom: 0; margin: 0px; '
					     . 'background-color: WhiteSmoke; cursor: pointer; overflow-y: auto; border-color: #2F2F2F; border-width: 3px; border-style: solid;" '
				     . 'onclick ="CloseAllPopups(); event.stopPropagation();" '
					     . 'ontouchstart = "OnTouchStart(event);" '
					     . 'ontouchend = "OnTouchEnd(event);" '
					     . 'onmousedown = "OnMouseDown(event);" '
					     . 'onmouseup = "OnMouseUp(event);" '
					     . '>'		;
	       BookSummary::PrintSummary($book, $searchString, true);
		   echo "</div>\n";
	       
	       echo "</td>\n";
	     
	       
	       echo "</tr>\n";
	     
        
		 }
		 
		 ?>
		 </tbody>
		 </table>
		 </main>
 </body> 