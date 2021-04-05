<!DOCTYPE html>
<?php
include_once "datetime.php";
include_once "database.php";
include_once "localization.php";
include_once "summary.php";

if (isset($_GET["debug"])) {
    error_reporting(- 1);
    ini_set('display_errors', 'On');
}

set_time_limit(60);
setlocale(LC_ALL, Localization::getText("LanguageLocale"));

$year = "";
if (isset($_GET["year"]))
{
    $year = $_GET["year"];
}

$currentYear = date("Y"); 
$currentMonth = date("m");

$showHiddenBooks = Config::getShowHiddenBooks();

$prefix = Config::getPrefix();
?>
<html lang="<?php echo Localization::getText("LanguageTag"); ?>" dir="<?php echo Localization::getText("LanguageDirection"); ?>">
<head>
<title><?php echo Localization::getText("Title"); ?></title>
 <link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="tablesort.js"></script>
<script src="mylibrary.js"></script>
<script>

  function KeyHandler (evt) {		
	  	
		switch (event.key) {
        	case "Tab":
        		if (event.shiftKey)
        			ClickOnElement("PREV");
        		else
        			ClickOnElement("NEXT");
        		break;		
        	case "ArrowLeft":
    			HandlePrevious();
    			break;
    		case "ArrowRight":
    			HandleNext();
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
			default:
				return;
		}
		evt.stopPropagation();
}
  	document.addEventListener('keydown',  KeyHandler);


  
  </script>

  <link rel="stylesheet" href="mylibrary.css" />
  <style type="text/css">
  	
 
.main {

  height: 100%;
  width:100%;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #FFFFFF;
  color: #000000
  overflow-x: hidden;
  overflow-y: scroll;
  padding-top: 4px;
  display: inline-block;
}

table tr th,
table tr td {
  border-right: 1px dotted;
}

table tr td {
  border-top:  1px dotted;
}

.caption {
	 font-weight: bold;
}
  
  </style>
</head>
<body>

 <?php
	  $database = new MyLibraryDatabase($showHiddenBooks);
	  $books = $database->getBooksByReadingDate();
	  $minYear = 1900;
	  if (count($books) > 0){
	      $minYear = intval($books[0]["STARTYEAR"]);
	  }
	  
	  ?>
	  <div id="navigation" class="sidenav"  >
	  
	  
	  <?php 
	  echo '<p>';
	  if ($year > $minYear)
	       echo '<a id="PREV" href="?'.  $prefix .  'year=' . ($year-1) . '">' . ($year-1) .'◄</a> ' ;
	  if ($year < $currentYear)
	       echo '<a id="NEXT"  href="?'.  $prefix .  'year=' . ($year+1) . '">►' . ($year+1) .'</a>' ;
       echo '</p>';
	  echo '<p>';
	  echo "<a href='#top'>" . $year . "</a><br/>";
	  
	  foreach (Localization::getMonthNames() as $key => $monat)
	  {
	      if ($year < $currentYear || $key <= $currentMonth)
	         echo '<a href="#mon' . $key . '">' . $monat . "</a><br/>";	      
	  }
	  
	  echo '<a href="#summary">' . Localization::getText("Summary") . '</a>';
	  echo '</p>';
	  ?>
	  <p><a href="mylibrary.php?<?php echo $prefix; ?>" ><?php echo Localization::getText("MainPage"); ?></a></p>
	  </div>
	  <div id="main" class="main">
	  <h1 id="top"><?php echo Localization::getText("Title"); ?></h1>
	  <h2><?php echo Localization::getTextWithParams("ReadInYear", $year); ?></h2>
	  <?php
	  
	  echo  "<p>\n";
	  $region = Config::getRegion();
	  $monthLengths = DatetimeUtil::getMonthLength($year);
	  $monate = Localization::getMonthNames();
	  $startedYear = 0;
	  $endedYear = 0;
	  $startedPerMonth = array();
	  $endedPerMonth = array();
	  
	  for ($month = 1; $month <= 12; $month ++)
	  {
	      if ($year == $currentYear && $month > $currentMonth)
	          break;

	      echo "<h3 id='mon" . $month . "'>" . $monate[$month] . " " . $year . "</h3>";
	      ?>
           <table style="text-align: left; table-layout: fixed; max-width: 84% ;">
           <col style="width:22%;max-width:23%;" />
           <col style="width:5%;max-width:5%;" />
           <col style="width:5%;max-width:5%;" />
           <col style="width:5%;max-width:5%;" />
            <?php
           
           	    for ($col = 1; $col <= 31; $col++)
           	    {
           	        if ($monthLengths[$month] >= $col)
           	        {
           	            echo '<col style="width:0.5%; max-width: 0.5%; ';
           	            $currentWeekdayHere = date("w",strtotime ( $year . "-" . $month . "-" . $col));
           	            $currentDate = $year . "-" . $month . "-" . $col;
           	            $feastInfo =DateTimeUtil::getFeastInfo(strtotime($currentDate));
           	            $isFeast =  $feastInfo["feast"];
           	            $isHalf = $feastInfo["feast"] && $feastInfo["half"];
           	            if (!empty($feastInfo["arStateShort"]) && !in_array($region, $feastInfo["arStateShort"]))
           	                $isFeast = false;
           	            if (strtotime(date("y-m-d")) == strtotime(date("y-m-d", strtotime($year . "-" . $month . "-" . $col))))
           	                echo " background-color: PaleGreen;";
           	            else if ($isHalf)
           	                echo " background-color: PapayaWhip;";
           	            else if ($isFeast)
           	                echo " background-color: PeachPuff;";
           	            else if ($currentWeekdayHere == "0")
       	                    echo " background-color: #bbbbbb;";
       	                else if ($currentWeekdayHere == "6")
       	                    echo " background-color: #dddddd;";
       	                echo '"/>';
           	        }
           	        else
           	            echo '<col style="width:0.5%; max-width: 0.5%; border-left: 0px; display:none; "/>';
           	        
           	     
           	    }
        	?>
        	<thead>
           	<tr>
           	  <th>Titel</th>
           	  <th>von</th>
           	  <th>bis</th>
           	  <th>Dauer</th>
           	 <?php
           	    for ($day = 1; $day <= 31; $day++)
           	    {
           	        if ($monthLengths[$month] < $day)
           	            echo "<th style='display:none; '></th>";
                    else
                    { 
                        echo "<th";
                        $currentDate = $year . "-" . $month . "-" . $day;
                        $title = "";
                        $feastInfo = DateTimeUtil::getFeastInfo(strtotime($currentDate));
                        
                        if (strtotime(date("y-m-d")) == strtotime(date("y-m-d", strtotime($year . "-" . $month . "-" . $day))))
                        {
                            $title = "Heute ";
                        }
                        
                        if ($feastInfo["feast"])
                        {
                            $title = $title .  $feastInfo["feastName"];
                            if (!empty($feastInfo["arStateShort"]))
                                $title = $title . " " . implode(', ',  $feastInfo["arStateShort"]);
                        }
                       
                        if ($title !== "")
                        {
                            echo ' title="'. htmlspecialchars($title) . '"';
                        }
                        echo ">" . $day. "</th>";
                    }
           	    }
           	 ?>
           	</tr>
           	</thead>
           	<tbody>
           	 <?php
           	 $started = 0;
           	 $ended = 0;
           	 
           	 foreach ($books as $row)
             {   
                   $id = $row['ID'];
                   $title = $row["TITLE"];
                   $authorInfo =  implode(", ", $row["AUTHORNAMES"]);
                   $imagefile = $row["IMAGEPATH"];
                   $ausgeliehen = $row["ISBORROWED"];
                   $givenway = $row["ISGIVENAWAY"];
                 
                   $authorInfo = "(" . $authorInfo . ")";
                   $startDate =  $row["StartDate"];
                   $endDate = $row["EndDate"];
                   $startDate2 = str_replace("/", ".", $row["StartDate"]);
                   $endDate2 = str_replace("/", ".", $row["EndDate"]);
                   $startDateFormated = $row["READSTART"];
                   $endDateFormated = $row["READEND"];
                   if ($endDateFormated === '')
                       $endDateFormated = '<span style="display: block; width: 100%;text-align: center">...</span>';
                   
                   if (is_null($endDate))
                       $endDate = "";
                   $currentDay = 1;
                   $currentDate = $currentDay . "." . $month . "." . $year;
                   $beginOfMonth = "1." . $month . "." . $year;
                   $endOfMonth = $monthLengths[$month]. "." . $month . "." . $year;
                   
                   if ($endDate === "")
                       $duration = strtotime(date('Y-m-d')) - strtotime($startDate);
                   else
                       $duration = strtotime($endDate) - strtotime($startDate);
                   $dayCount = (int)( $duration / (24 * 60 * 60) );
                   $durationDays =  $dayCount . ' '.  Localization::getTextForCount("Days", $dayCount);
                   $durationDays = $row["READDAYS"];
                   $inMonth = false;
                   if (
                       (strtotime($startDate2) < strtotime($beginOfMonth) 
                       && ($endDate === "" || strtotime($endDate2) > strtotime($beginOfMonth))
                       )                       
                       )
                       $inMonth = true;
                   if (
                       (strtotime($startDate2) >= strtotime($beginOfMonth)
                           && strtotime($startDate2) <= strtotime($endOfMonth))
                      )
                       $inMonth = true;
                      
                   if ($inMonth)
                   {
                       echo "<tr>";                                          
                       echo '<td  onclick="ShowSummaryPopup(document.getElementById(\'SummaryPopup' . $id . '\'));" ' . ">"
            	           . '<img style="max-width:24px; height: 24px; " '
            	           . ' title="' . htmlspecialchars($title) . '" '
            	           . 'src="' . $imagefile. '" />'
            	                        . "\n";
                       echo " " . htmlspecialchars($title) . " <span class='subtitle'>" . htmlspecialchars($authorInfo) . "</span>";
                       
            	       if ($ausgeliehen)
            	           echo " <img src='borrow.png' width='24px;' alt='" .Localization::getText("Borrowed") . "' title='" .Localization::getText("Borrowed") . "'/>";
        	           if ($givenway)
        	               echo " <img src='givenaway.png' width='24px;' alt='" .Localization::getText("GivenAway") . "' title='" .Localization::getText("GivenAway") . "'/>";
        	           echo "\n". '<DIV id="SummaryPopup' . $id . '" style="display: none; '
           	            . 'position: fixed; width: 100%; height: 100%; z-index: 2; top:0; left: 0; right: 0; bottom: 0; margin: 0px; '
           	            . 'background-color: WhiteSmoke; cursor: pointer; overflow-y: auto; border-color: #2F2F2F; border-width: 3px; border-style: solid;" '
           	                . 'onclick ="CloseAllPopups(); event.stopPropagation();" '
           	                . 'ontouchstart = "OnTouchStart(event);" '
           	                . 'ontouchend = "OnTouchEnd(event);" '
           	                . 'onmousedown = "OnMouseDown(event);" '
           	                . 'onmouseup = "OnMouseUp(event);" '
           	                . '>'		;
           	                BookSummary::PrintSummary($row, "", true);
           	                echo "</div>\n"; 
            	       echo "</td>";
            	       echo "<td>" . $startDateFormated . "</td>";
            	       echo "<td>" . $endDateFormated . "</td>";
                       echo "<td>" . $durationDays . "</td>";
                       
                       
                       for ($day = 1; $day <= 31; $day++)
                       {
                           if ($monthLengths[$month] < $day)
                               echo "<td style='display:none; '></td>";
                           else
                           {
                               $compareDate = strtotime($day . "." . $month . "." . $year);
                               if (strtotime($startDate2) == $compareDate )
                               {
                                   if (strtotime($startDate2) == strtotime($endDate2))
                                   {    
                                       echo "<td>├┤</td>";
                                       $started++;
                                       $ended++;
                                   }
                                   else
                                   {
                                       echo "<td>├─</td>";
                                       $started ++;
                                   }
                               }
                               else if (strtotime($endDate2) == $compareDate )
                               {
                                   echo "<td>─┤</td>";
                                   $ended ++;
                               }
                               else if  ( 
                                   (strtotime($startDate2) < $compareDate)
                                   && (strtotime($endDate2) > $compareDate || $endDate2 === '')
                                   && ($compareDate <= strtotime(date('Y-m-d')))
                                   )
                               {
                                   if ($compareDate == strtotime(date('Y-m-d')))
                                       echo "<td>…</td>";
                                   else
                                       echo "<td>──</td>";
                               }
                               else
                                   echo "<td></td>";
                           }
                       }
                       echo "</tr>";
                   }                 
                   
             }
           	?>
           	</tbody>
           </table>
           
           <?php
           echo "<p><i>" . Localization::getTextWithParams("StartEndInfo", $started, $ended) . "</i></p>";
           $startedYear += $started;
           $endedYear += $ended;
           
           $startedPerMonth[$month] = $started;
           $endedPerMonth[$month] = $ended;
       }


	  ?>
	  <h3 id="summary"><?php echo Localization::getTextWithParams("CompleteYearCaption", $year)  ?></h3> 
	  <table>
    	  <thead>
               	<tr>
               	  <th><?php echo Localization::getText("Month")  ?></th>
               	  <th><?php echo Localization::getText("Started")  ?></th>
               	  <th><?php echo Localization::getText("Ended")  ?></th>
                 </tr>
          </thead>
          <tbody>
         	 
          <?php 
          
          $endMonth = 12;
          if ($year == $currentYear)
              $endMonth = $currentMonth;
          for ($month = 1; $month <= $endMonth; $month ++)
          {
              echo '<tr>';
              echo '<td>' . $monate[$month] . '</td>';
              echo '<td>' . $startedPerMonth[$month] . '</td>';
              echo '<td>' . $endedPerMonth[$month] . '</td>';
              echo '</tr>';
          }
          
          echo '<tr>';
          echo '<td>' . Localization::getTextWithParams("CompleteYearCaption", $year) . '</td>';
          echo '<td>' . $startedYear . '</td>';
          echo '<td>' . $endedYear . '</td>';
          echo '</tr>';
          ?>
          	
          </tbody>
	  </table>
	  </p>
	 </div> 
</body>
</html>