/* links to OpenLibrary are only displayed when it is sure, that there is an exisiting entry there */
function showOlbLink(element, isbnToLookFor){
				
				var request = new XMLHttpRequest();
				request.open('GET', 'https://openlibrary.org/api/books?bibkeys=ISBN:' + isbnToLookFor + '&format=json', true);
				request.send(null);
				request.onreadystatechange = function () {
				if (request.readyState === 4 && request.status === 200) {
					if (request.responseText !== '{}')
						element.style.display = 'inline';
				}
				}
			}
	

	function getElementsStartsWithId( id ) {
	  var children = document.body.getElementsByTagName('*');
	  var elements = [], child;
	  for (var i = 0, length = children.length; i < length; i++) {
		child = children[i];
		if (child.id.substr(0, id.length) == id)
		  elements.push(child);
	  }
	  return elements;
	}

	function getVisibleElementsStartsWithId( id ) {
		  var children = document.body.getElementsByTagName('*');
		  var elements = [], child;
		  for (var i = 0, length = children.length; i < length; i++) {
			child = children[i];
			if (child.id.substr(0, id.length) == id){
				if (child.style.display !== 'none' && child.style.display !== '')
					elements.push(child);
			}
		  }
		  return elements;
		}

	function FollowLink(elem){
		if (elem.style.display !== 'none'){
			
			elem.click();
		}
	}

	function FollowLinkDnb(elem){
		if (elem.id.length >'ImgDnbSummaryPopup'.length){
			var summaryPopupId = elem.id.substr('ImgDnb'.length);
			var popupElem = document.getElementById(summaryPopupId);
			if (popupElem != null){
				if (popupElem.style.display != 'none'){
					elem.click();
				}
			}
		}
		
		
	}

	function ShowSummaryPopupForBigCover(elem){
		if (elem === null)
			return;
		var popupElem = document.getElementById("ShowPopup" + elem.id);
		if (popupElem != null){
			popupElem.click();
		}
	}

	function ShowBigCoverInSummary(){
		var dnbCovers = getVisibleElementsStartsWithId("ImgDnbSummaryPopup");
		dnbCovers.forEach(FollowLinkDnb);
		var olbCovers = getVisibleElementsStartsWithId("ImgOlbSummaryPopup");
		olbCovers.forEach(FollowLink);
	}

	function ShowSummaryPopupForCover(){
		var bigCovers = getVisibleElementsStartsWithId("BigCover");
		bigCovers.forEach(ShowSummaryPopupForBigCover);
	}

	
	function SetMainScrollbar(mode)
	{
		var bodyElem = document.getElementById('body');
		
		if (bodyElem !== null)
		{
			bodyElem.style.overflow = mode;
		}
	}
	
	function ShowSummaryPopup(elem)
	{
		elem.style.display = 'block';
		var dnbImg = document.getElementById('ImgDnb' + elem.id);
		if (dnbImg !== null){
			dnbImg.src = dnbImg.dataset.src;
			dnbImg.style.display = 'block';
		}
		var olbImg = document.getElementById('ImgOlb' + elem.id );
		if (olbImg !== null)
		{
			olbImg.src = olbImg.dataset.src;					
		}
		
		var fromAppImg = document.getElementById('ImgFromApp' + elem.id );
		if (fromAppImg !== null)
		{
			fromAppImg.src = fromAppImg.dataset.src;
			fromAppImg.style.display = 'inline';					
		}
		
		var olbLinkSummaryPopup = document.getElementById('OlbLink' + elem.id );
		if (olbLinkSummaryPopup !== null)
			showOlbLink(olbLinkSummaryPopup, olbLinkSummaryPopup.dataset.isbn);

		var dnbLinkSummaryPopup = document.getElementById('DnbLink' + elem.id );
		if (dnbLinkSummaryPopup !== null)
			dnbLinkSummaryPopup.style.display = 'inline';	
		
		var amazonLinkSummaryPopup = document.getElementById('AmazonLink' + elem.id );
		if (amazonLinkSummaryPopup !== null)
			amazonLinkSummaryPopup.style.display = 'inline';		

				
		SetMainScrollbar('hidden');
	
	}
	
	function ShowBigCoverPopup(elem)
	{
		elem.style.display = 'block';
		var dnbImg = document.getElementById('Img' + elem.id);
		if (dnbImg !== null)
			dnbImg.src = dnbImg.dataset.src;
	}
	
	
	function ClosePopup(elem)
	{
		elem.style.display = 'none';				
	}

	function CloseAllPopups()
	{
		var elems = getElementsStartsWithId("SummaryPopup");
		elems.forEach(ClosePopup);
		var coverElems = getElementsStartsWithId("BigCover");
		coverElems.forEach(ClosePopup);
		var expandedElems = getElementsStartsWithId("Expanded");
		expandedElems.forEach(ClosePopup);
		var dnbImagesInPopup = getElementsStartsWithId("ImgDnbSummaryPopup");
		dnbImagesInPopup.forEach(ClosePopup);
		var olbImagesInPopup = getElementsStartsWithId("ImgOlbSummaryPopup");
		olbImagesInPopup.forEach(ClosePopup);
		var dnbLinksInPopup = getElementsStartsWithId("DnbLinkSummaryPopup");
		dnbLinksInPopup.forEach(ClosePopup);
		var olbLinksInPopup = getElementsStartsWithId("OlbLinkSummaryPopup");
		olbLinksInPopup.forEach(ClosePopup);
		var amazonLinksInPopup = getElementsStartsWithId("AmazonLinkSummaryPopup");
		amazonLinksInPopup.forEach(ClosePopup);
		SetMainScrollbar('auto');
	}
	
	function HandleEscape()
	{			
		CloseAllPopups();
	}
	
	function getCurrentSummaryPopup()
	{
		var elems = getElementsStartsWithId("SummaryPopup");
		var elem;
		for (var i = 0, length = elems.length; i < length; i++) 
		{
			elem = elems[i];
			if (elem.style.display !== 'none')
			{
				return elem;
			}
		}
		return null;
	}
	
	function getPreviousCurrentAndNextSummaryPopup()
	{
		var elems = getElementsStartsWithId("SummaryPopup");
		var elem;
		var previous = null;
		var current = null;
		var next = null;
		var first = null;
		var last = null;
		for (var i = 0, length = elems.length; i < length && next === null; i++) 
		{
			elem = elems[i];
			if (i == 0)
				first = elem;
			if (i == elems.length)
				last = elem;
			
			if (elem.style.display !== 'none')
			{
				current = elem;
			}
			else
			{
				if (current !== null && next === null)
				next = elem;
			}
			if (current === null)
				previous = elem;
			
		}
		if (previous === null)
			previous = last;
		if (next === null)
			next = first;
		
		var result = [previous, current, next];
		return result;
	}

	function getActiveElementByPrefix(prefix)
	{
		var elems = getElementsStartsWithId(prefix);
		var result = [];
		var elem;
		for (var i = 0, length = elems.length; i < length; i++) 
		{
			 elem = elems[i];
			 if (elem.style.display !== 'none')
				 return elem;
		}
		return null;
	}
	

	function getActiveCoverPopup()
	{
		return getActiveElementByPrefix("BigCover");
	}

	function GoBackToSummaryIfCoverPopupIsActive()
	{
		var activeCoverPopup = getActiveCoverPopup();
		if (activeCoverPopup !== null)
			ShowSummaryPopupForCover();
	}
	
	function HandleNext()
	{
		GoBackToSummaryIfCoverPopupIsActive();
		var prevCurrNext = getPreviousCurrentAndNextSummaryPopup();
		CloseAllPopups();
		if (prevCurrNext[2] !== null)
		{
			ShowSummaryPopup(prevCurrNext[2]);
		}
	}
	
	function HandlePrevious()
	{
		GoBackToSummaryIfCoverPopupIsActive();
		var prevCurrNext = getPreviousCurrentAndNextSummaryPopup();
		CloseAllPopups();
		if (prevCurrNext[0] !== null)
		{
			ShowSummaryPopup(prevCurrNext[0]);
		}
	}

	function ClickOnActiveElementByPrefix(prefix)
	{
		var olbLink = getActiveElementByPrefix(prefix);
		if (olbLink !== null)
			olbLink.click();
	}

	function OpenDnbLink()
	{
		ClickOnActiveElementByPrefix('DnbLinkSummaryPopup');
	}

	function OpenOlbLink()
	{
		ClickOnActiveElementByPrefix('OlbLinkSummaryPopup');
	}

	function OpenAmazonLink()
	{
		ClickOnActiveElementByPrefix('AmazonLinkSummaryPopup');
	}

	function FocusSearchField()
	{
		CloseAllPopups();
		var elem = document.getElementById('searchfield');
	  	if (elem !== null)
	  	{
		  	elem.focus();
		  	elem.scrollIntoView();
		  	elem.select();
	  	}
	}

	function ClearFilter()
	{
		CloseAllPopups();
		var elem = document.getElementById('clearbutton');
	  	if (elem !== null)
	  	{
		  	elem.click();
	  	}
		
	}
	

	function ClickOnElement(id){
		var element = document.getElementById(id);
		element.click();
	}

	function ScrollElement(element, offset) {
		element.scrollTop += offset;
	}

	function ScrollTop(element){
		element.scrollTop = 0;
	}

	function ScrollEnd(element){
		element.scrollTop = element.scrollHeight;
	}
	  
	
	function KeyForPopupHandler (evt) {			
		switch (event.key) {
			case "Escape":
				HandleEscape();
				break;
			case "ArrowLeft":
				HandlePrevious();
				break;
			case "ArrowRight":
				HandleNext();
				break;			
			case 'd':
				if (evt.ctrlKey && evt.altKey)
					OpenDnbLink();
				break;
			case 'o':
				if (evt.ctrlKey && evt.altKey)
					OpenOlbLink();
				break;
			case 'a':
				if (evt.ctrlKey && evt.altKey)
					OpenAmazonLink();
				break;	
			case 'f':
				if (evt.ctrlKey && evt.altKey)
					FocusSearchField();
				break;
			case 'c':
				if (evt.ctrlKey && evt.altKey)
					ClearFilter();
				break;
		}

  }
	var startX = null;
	var startY = null;
	var starttime = null;
	
	// min. duration for wiping
	var durationMin = 100;

	// min. distance for wiping on x axis
	var distanceTraveledMin = 100;

	// max. discrepancy (tolerance) on y axis for wipings
	var courceTolerance = 150;

	
	function OnTouchStart(e)
	{
		startX = e.changedTouches[0].pageX;
		startY = e.changedTouches[0].pageY;
		starttime = new Date().getTime();
	}
	
	function OnTouchEnd(e)
	{
		var endX = e.changedTouches[0].pageX;
		var endY = e.changedTouches[0].pageY;
		var endtime = new Date().getTime();

		verifyHorizontalSwipe(e,endX, endY, endtime);
	}
	
	function OnMouseDown(e)
	{
	
		startX = e.pageX;
		startY = e.pageY;
		starttime = new Date().getTime();
	}

	function OnMouseUp(e)
	{
		var endX = e.pageX;
		var endY = e.pageY;
		var endtime = new Date().getTime();

		return verifyHorizontalSwipe(e, endX, endY, endtime);
	}
	
	function verifyHorizontalSwipe(e, endX, endY, endtime) 
	{
		// duration of gesture
		var duration = endtime - starttime;
		// distance of gesture in pixels
		var distanceTraveled = endX - startX;
		// deviation of gestures on y axis in pixels
		var deviation = Math.abs(endY - startY);

		if (duration >= durationMin
		&& Math.abs(distanceTraveled) >= distanceTraveledMin
		&& deviation <= courceTolerance) {
			if (Math.sign(distanceTraveled) == 1) {
				//from left to right (previous)
				HandlePrevious();
				e.stopPropagation();
				return false;
			}
			else if (Math.sign(distanceTraveled) == -1) {
				//from right to left (next)
				HandleNext();
				e.stopPropagation();
				return false;
			}
		}
		return true;
	} 


	
	
	
