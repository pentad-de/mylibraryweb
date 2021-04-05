<?php 
#include_once("localization.php");

class BookSummary{
    static public function PrintSummary($book, $searchString, $showCloseButton){
        
    ?>
        <div class="wrapper">
        <div class="close">
        <?php if ($showCloseButton) { ?>
       		 <DIV style="display: inline; border-style:solid; border-width:1px; font-weight:bold; font-size: x-large; background-color:lightgray; width: 1.5rem; text-align: center; vertical-align: middle; height: 1.5rem; " >&nbsp;&times;&nbsp;</DIV>
        <?php } ?>
        </div>
        <div class="type">
        <?php
        if ( $book["ISBORROWED"]){
          	echo '<IMG SRC="borrow.png" ALT="' . Localization::getText("Borrowed") 
          	. '" TITLE="' . Localization::getText("Borrowed") . '" style="height: 1.5rem;"/>';
        }
        if ( $book["ISGIVENAWAY"]){
            echo '<IMG SRC="givenway.png" ALT="' . Localization::getText("GivenAway")
            . '" TITLE="' . Localization::getText("GivenAway") . '" style="height: 1.5rem;"/>';
        }
        
        $id = $book["ID"];
        if ($book["BOOKTYPE"] == 'b')
        {
            echo '<IMG SRC="comic.png" ALT="' . Localization::getText("ComicBook")
            . '" TITLE="' . Localization::getText("ComicBook") . '" style="height: 1.5rem;"/>';
        }
        if ($book["BOOKTYPE"] == 'c')
        {
            echo '<IMG SRC="ebook.png" ALT="' . Localization::getText("EBook")
            . '" TITLE="' . Localization::getText("EBook") . '" style="height: 1.5rem;"/>';
          }
          if ($book["BOOKTYPE"] == 'd')
          {
              echo '<IMG SRC="headphones.png" ALT="' . Localization::getText("AudioBook")
              . '" TITLE="' . Localization::getText("AudioBook") . '" style="height: 1.5rem;"/>';
          }
         
	      if ($book["HASISBN"])
	      {
	          ?>
	          <a href="https://portal.dnb.de/opac.htm?referrer=Wikipedia&method=enhancedSearch&index=num&term=<?php echo $book["ISBN"]; ?>&operator=and"
	             accesskey="1" 
				 target="_blank" onclick="event.stopPropagation();">
	          <IMG ID="DnbLinkSummaryPopup<?php echo $id; ?>"
	          	   SRC="info.png" style="height: 1.5rem; display: none;"
	          	   title="<?php echo Localization::getTextWithParams("DnbLinkForBook", htmlspecialchars($book['TITLE'])); ?>"
	          	   alt="<?php echo Localization::getTextWithParams("DnbLinkForBook", htmlspecialchars($book['TITLE'])); ?>"
	          	   data-isbn="<?php echo $book['ISBN13']; ?>"
	          	   />
	          </a>
	          
	          <a href="https://openlibrary.org/isbn/<?php echo $book["ISBN"]; ?>" 
	          	 accesskey="2"
				 target="_blank" onclick="event.stopPropagation();">
	          <IMG ID="OlbLinkSummaryPopup<?php echo $id; ?>"
	          	   SRC="OpenLibrary.png" style="display: none; height: 1.5rem; "
	          	   title="<?php echo Localization::getTextWithParams("OlLinkForBook", htmlspecialchars($book['TITLE'])); ?>"
	          	   alt="<?php echo Localization::getTextWithParams("OlLinkForBook", htmlspecialchars($book['TITLE'])); ?>"
	          	   data-isbn="<?php echo $book['ISBN13']; ?>"
	          	   />
	          </a>
	          
	          
	          <?php
	      }
	      
	      if ($book['AMAZON_URL'] !== '' && !is_null($book['AMAZON_URL']))
          {
              ?>
              <a href="<?php echo $book['AMAZON_URL']; ?>"
           	   accesskey="3"
                 target="_blank" onclick="event.stopPropagation();" >
              <img SRC="a.png" style="height: 1.5rem;"   
                   STYLE="display: none; "
                   ID="AmazonLinkSummaryPopup<?php echo $id; ?>"	                    
                   title="<?php echo Localization::getTextWithParams("ALinkForBook", htmlspecialchars($book['TITLE'])); ?>"
                   alt="<?php echo Localization::getTextWithParams("ALinkForBook", htmlspecialchars($book['TITLE'])); ?>" 
              />
              </a>
              <?php
              
          }

		          ?>
			  </div>
			 <div class="description">
			  <h2><?php echo htmlspecialchars($book['TITLE']); ?></h2>
			  <?php
			  if (!is_null($book['SerieTitle']) && $book['SerieTitle'] !== '')
			  {
			      echo '<p>' . htmlspecialchars($book['SerieTitle']);
			      if (!is_null($book['SerieVolume']) && $book['SerieVolume'] !== '')
			          echo Localization::getTextWithParams("SerieVolumeAppendix", $book['SerieVolume']);
		          echo'</p>';
			  }
			  
			  ?>
			  </div>
			  
			  	  <div class="cover">
			  <?php 
			  
			  
			   echo '<img width="128px" height="191px" id="ImgFromAppSummaryPopup' . $id . '"'
			   . ' title="' . htmlspecialchars($book['TITLE']) . '" style= "display: none; border: 1px solid gray;" '
			   . 'DATA-SRC="' . $book['IMAGEPATH']. '" />' . "\n";
			   
			  if ($book["HASISBN"])
			  {
			      ?>
			     <IMG class="coverimage" 
    				  	DATA-SRC="https://portal.dnb.de/opac/mvb/cover.htm?isbn=<?php echo $book["ISBN13"]; ?>" 
    				  	ID="ImgDnbSummaryPopup<?php echo $id; ?>"
    				  	STYLE="display: none; border: 2px solid #0000aa;"
    				  	ALT="<?php echo Localization::getText("DnbCover"); ?>"
    				  	TITLE="<?php echo Localization::getText("DnbCover"); ?>"
    				  	onerror="this.style.display = 'none';
    				  		document.getElementById('ImgOlbSummaryPopup<?php echo $id; ?>').style.display='block'; "
    				  	onclick="CloseAllPopups();
    				  		ShowBigCoverPopup(document.getElementById('BigCoverDNB<?php echo $id; ?>'));
    				  		event.stopPropagation();
    				  	" 
			  	/>    				
				<IMG class="coverimage" 
    				  	DATA-SRC="https://covers.openlibrary.org/b/isbn/<?php echo $book["ISBN13"]; ?>-L.jpg?default=false"
    				  	STYLE = "display: none; border: 2px solid #0000aa;" 
    				  	ID="ImgOlbSummaryPopup<?php echo $id; ?>"
    				  	TITLE="<?php echo Localization::getText("OlCover"); ?>"
    				  	ALT="<?php echo Localization::getText("OlCover"); ?>"
    				  	onerror="this.style.display = 'none';"            				  	
			  	/>    				
			      
			      <?php     				      
			  }
			  
			  ?>
			  
			   </div>
			  
			<div class="descriptiondetail">
			
			<?php 
			
			$authorsCount = count($book["AUTHORNAMES"]);
			if ($authorsCount > 1)
			{
			    echo '<div style="margin-top: 12px"><span class="caption">' 
                    . Localization::getTextForCount("AuthorCaption", $authorsCount ) 
                    . '</span><br/>'      
       		        . '<ul style="margin: 0"><li>'
                    . implode("</li>\n<li>", $book["AUTHORNAMES"] )
			        . '</li></ul>';
			}
			else
			{
			    echo '<div style="margin-top: 12px"><span class="caption">' . Localization::getTextForCount("AuthorCaption", $authorsCount ) . '</span>' ;
			    if (Config::hasNoAuthor($book))
			        echo Localization::getText("UnknownAuthor");
			    else
			        echo implode(" ", $book["AUTHORNAMES"]);
			}
			echo "</div>\n";
			if (isset($book["BOOKTYPENAME"]))
			{
			    if ($book["BOOKTYPENAME"] !== '')
			        echo '<div style="margin-top: 4px"><span class="caption">' . $book["BOOKTYPENAME"] . '</div>';
			}
			$ccCount = count($book["CUSTOMCATEGORIES"]);
			if ($ccCount > 0)
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' .  Localization::getTextForCount("CategoryCaption", $ccCount )  . '</span> ';
			    if ($ccCount > 1)
			        echo "<ul style='margin: 0'>\n<li>";
		        echo implode("</li>\n<li>", $book["CUSTOMCATEGORIES"]);
		        if ($ccCount > 1)
		            echo "</li>\n</ul>\n";
			    echo "</div>\n";
			}
			
			if ($book['PUBLISHER'] !== '' && !is_null($book['PUBLISHER']))
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' . Localization::getText("PublisherCaption") . '</span> ' . htmlspecialchars($book['PUBLISHER']) . ' </div>';
			}
			// PUBLISHED_DATE
			if ($book['PUBLISHED_DATE'] !== '' && !is_null($book['PUBLISHED_DATE']))
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' . Localization::getText("PublishedDate") . '</span> ' . htmlspecialchars($book['PUBLISHED_DATE_FORMATTED']) . ' </div>';
			}
			if ($book['ISBN'] !== '' && !is_null($book['ISBN']))
			{
			    if (strlen($book['ISBN']) >= 10)
			        echo "\n". '<div style="margin-top: 4px"><span class="caption">' . Localization::getText("IsbnCaption") . '</span> ' . htmlspecialchars($book['ISBN']) . ' </div>';
		        else
		            echo "\n". '<div style="margin-top: 4px"><span class="caption">' . Localization::getText("BookNumberCaption") . '</span> ' . htmlspecialchars($book['ISBN']) . ' </div>';
			}
			if ($book['PAGES'] !== '' && $book['PAGES'] !== 0 && !is_null($book['PAGES']))
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' . Localization::getText("PagesCaption") . '</span> ' . htmlspecialchars($book['PAGES']) . ' </div>';
			}
			$languageCount = count($book["LANGUAGETAGS"]);
			if ($languageCount > 0)
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' .  Localization::getTextForCount("LanguageCaption", $languageCount )  . '</span> ';
			    foreach ($book["LANGUAGETAGS"] as $languageTag)
			    {
			        echo Localization::getLanguageByTag($languageTag) . " ";
			    }
			    echo "</div>\n";
			}
			
			$locationCount = count($book["LOCATIONS"]);
			if ($locationCount > 0)
			{
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' .  Localization::getTextForCount("LocationCaption", $locationCount )  . '</span> ';
		        echo implode(" ", $book["LOCATIONS"]);
		        echo "</div>\n";
			}
			
	       if (isset($book["COMMENTDICTIONARYMULTI"])){
	           echo "\n". '<div style="margin-top: 4px"><span class="caption">' .  Localization::getTextForCount("Comments", count($book["COMMENTDICTIONARY"]))  . '</span> ';
	           $firstComment = true;
	         
	           foreach ($book["COMMENTDICTIONARYMULTI"] as $commentKey => $commentValues){
	               if ($firstComment)
	                   $firstComment = false;
                   else
                       echo "<br/>\n";
                   
                   echo "<span class='caption'>" . Localization::getTextWithParams("CommentCaption", htmlentities($commentKey)) . "</span>";
                   
                   if (count($commentValues) > 1)
                       echo "<ul style='margin: 0;'>\n";
                   foreach ($commentValues as $dontuse => $commentValue)
                   {
                       if (count($commentValues) > 1)
                            echo "<li>\n";
                       $textKey = "CommentCaption";
                       if (is_null($commentValues) || $commentValue === '')
                           $textKey = "Comment";
                       echo htmlentities($commentValue);
                       if (count($commentValues) > 1)
                           echo "</li>\n";
                   }
                   if (count($commentValues) > 1)
                       echo "</ul>\n";
	           }
	           echo "</div>\n";
	       }
			       
			if ( $book["ISBORROWED"]){
			    echo "\n". '<div style="margin-top: 4px"><span class="caption">' .Localization::getText("Borrowed") . '</span></div>';
			}
			echo "\n". '<div style="margin-top: 4px"><span class="caption">' . $book["READINTERVAL"] . '</span></div>';
			echo "</div>\n";
			
			
			$summary = '';
			
			if ($book['SUMMARY'] !== '' && !is_null($book['SUMMARY']))
			{
			    $matches = array();
			    $pattern = "/<(p|span|b|strong|i|u) ?.*>(.*)<\/(p|span|b|strong|i|u)>/"; // Allowed tags are: <p>, <span>, <b>, <strong>, <i> and <u>
			    preg_match($pattern, $book['SUMMARY'], $matches);
			    
			    if (!empty($matches))
			    {
			        $summary = $book['SUMMARY'];
			    }
			    else
			    {
			        $summary = '<p style=" margin: 0; padding: 0;">'
			       . preg_replace('~\<br[ ]?[/]?\>[\s]*\<br[ ]?[/]?\>~s',
			           '</p><p style="margin-top: 0.5em">',
			           nl2br(htmlspecialchars($book['SUMMARY'])) )
			           . "</p>";
			    }
			}
			
			$searchStringWithReplacesBlanksForSummary = "";
			if ($searchString !== '')
			{
			    $toReplace = array(" ", "\t", "\n", ";", "'", "\"", ",", ".", "*", "?", "-", "_");
			    $searchStringWithReplacesBlanksForSummary= str_replace($toReplace, '_', $searchString);    				  
			}
			
			if ($summary !== '')
			{
			    
			    if (!is_null($searchString) && $searchString !== '' && $book['HitInSummary'] === 1)
			    {
			        $summaryCopy = $summary;
			        $regexpattern = '/' . str_replace("%", '.*', str_replace("_", "[ ]*", $searchStringWithReplacesBlanksForSummary)) . '/iU';
			        
			        $matches = array();
			        preg_match_all($regexpattern, $summary, $matches, PREG_OFFSET_CAPTURE);
			        $offset = 0;
			        $marker = "<span class='highlight'>";
			        $endmarker = "</span>";
			        foreach ($matches as $match)
			        {
			            foreach ($match as $singleMatch)
			            {
			                
			                $summaryCopy = substr_replace($summaryCopy, $marker, $singleMatch[1] +  $offset, 0);
			                $summaryCopy = substr_replace($summaryCopy, $endmarker, $singleMatch[1] + strlen($marker) + strlen($singleMatch[0]) + $offset, 0);
			                $offset = $offset + strlen($marker) + strlen($endmarker);
			            }
			            
			            if (count($match) > 0)
			                $summaryCopy = $summaryCopy . "<br/><span class='highlight'><span class='smalltext'>" 
			                 . Localization::getTextForCountWithParams("MatchesForSearchString",  count($match), count($match), htmlentities($searchString))
			              . "</span></span>";
			        }
			        $summary = $summaryCopy;
			    }
			    ?>
			     <div class="summary"><span class="caption"><?php echo Localization::getText("SummaryCaption"); ?></span></div>
			     <div class="summarycontent" 
			     	style="hyphens: auto; -webkit-hyphens: auto;"
			     	lang="<?php echo $book["LANGUAGETAG"]; ?>"
			     >
			     <?php echo $summary; ?>    				     
			     </div>
			 <?php 
			}
			?>
		    </div>
		    <?php 
		   
		    echo "\n";
    			    
    }
}

?>
		