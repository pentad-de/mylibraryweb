<?php

include_once "localization.php";

header('Content-Type: text/javascript');

?>

/**
 *  table sort from https://wiki.selfhtml.org/extensions/Selfhtml/frickl.php/Beispiel:JS-Anw-sortierbare-Tabellen-3.html#view_result
 *  published under CC-BY-SA 3.0 (de);
 *  extended by localization
 */

		( function() {

				"use strict";

				var tableSort = function(tab) {
					// Dokumentensprache ermitteln
					var doclang = document.documentElement.lang || "en"; 

					// Tabellenelemente ermitteln
					var thead = tab.tHead;
					if (thead) var tr_in_thead = thead.rows;
					if (tr_in_thead) var tabletitel = tr_in_thead[0].cells;
					if ( !(tabletitel && tabletitel.length > 0) ) { 
						console.error("Tabelle hat keinen Kopf und/oder keine Kopfzellen."); 
						return; 
					}
					var tbdy = tab.tBodies;
					if ( !(tbdy) ) { 
						console.error("Tabelle hat keinen tbody.");
						return; 
					}
					tbdy = tbdy[0];
					var tr = tbdy.rows;
					if ( !(tr && tr.length > 0) ) { 
						console.error("Tabelle hat keine Zeilen im tbody."); 
						return; 
					}
					var nrows = tr.length,
					    ncols = tr[0].cells.length;

					// Einige Variablen
					var arr = [],
					    sorted = -1,
					    sortbuttons = [],
					    sorttype = [];

					// Hinweistexte
					var sort_info, 
					    sort_hint;
				
					sort_info = {
						asc: "<?php echo Localization::getText("TableSortSortInfoAsc")?>",
						desc: "<?php echo Localization::getText("TableSortSortInfoDesc")?>",
					};
					sort_hint = {
						asc: "<?php echo Localization::getText("TableSortSortHintAsc")?>",
						desc: "<?php echo Localization::getText("TableSortSortHintDesc")?>",
				    };

					// Stylesheets für Button im TH
					var sortbuttonStyle = document.createElement('style'), 
					    stylestring = '.sortbutton { width: 100%; height: 100%; border: none; background-color: transparent; font: inherit; color: inherit; text-align: inherit; padding: 0; cursor: pointer; } ';		
					stylestring += '.sortierbar thead th span.visually-hidden { position: absolute !important; clip: rect(1px, 1px, 1px, 1px) !important; padding: 0 !important; border: 0 !important; height: 1px !important; width: 1px !important; overflow: hidden !important; white-space: nowrap !important; } ';
					stylestring += '.sortierbar caption span { font-weight: normal; font-size: .8em; } ';
					stylestring += '.sortbutton::after { display: inline-block; letter-spacing: -.2em; margin-left: .1em; width: 1.8em; font-size:smaller; } ';
					
					if (window.innerWidth < 1024){
						stylestring += '.sortbutton.unsorted::after { content: "↕"; } ';
						stylestring += '.sortbutton.sortedasc::after { content: "↑"; } ';
						stylestring += '.sortbutton.sorteddesc::after { content: "↓"; } ';
						}
					else {
						stylestring += '.sortbutton.sortedasc::after { content: "▲▽"; } ';
						stylestring += '.sortbutton.sorteddesc::after { content: "△▼"; } ';
						stylestring += '.sortbutton.unsorted::after { content: "△▽"; } ';
						}
					stylestring += '.sortbutton.sortedasc > span.visually-hidden:first-of-type { display: none; } ';
					stylestring += '.sortbutton.sorteddesc > span.visually-hidden:last-of-type { display: none; } ';
					stylestring += '.sortbutton.unsorted > span.visually-hidden:last-of-type { display: none; } ';
					sortbuttonStyle.innerText = stylestring;
					document.head.appendChild(sortbuttonStyle);

					var initTableHead = function(sp) { // Kopfzeile vorbereiten
						var sortbutton = document.createElement("button");
						sortbutton.type = "button";
						sortbutton.className = "sortbutton unsorted";
						sortbutton.addEventListener("click", function() { tsort(sp); }, false);
						sortbutton.innerHTML = "<span class='visually-hidden'>" + sort_hint.asc + "</span>" + "<span class='visually-hidden'>" + sort_hint.desc + "</span>" + tabletitel[sp].innerHTML;
						tabletitel[sp].innerHTML = "<span class='visually-hidden'>" + tabletitel[sp].innerHTML + "</span>";
						tabletitel[sp].appendChild(sortbutton);
						sortbuttons[sp] = sortbutton;
						tabletitel[sp].abbr = "";
					} // initTableHead

					var getData = function (ele, col) {
						var val = ele.textContent;
						// Tausendertrenner entfernen, und Komma durch Punkt ersetzen
						var tval = val.replace(/\s|&nbsp;|&#160;|\u00A0|&#8239;|\u202f|&thinsp;|&#8201;|\u2009/g,"").replace(",", ".");
						if (!isNaN(tval) && tval.search(/[0-9]/) != -1) return tval; // Zahl
						sorttype[col] = "s"; // String
						return val;
					} // getData		

					var vglFkt_s = function(a, b) {
						return a[sorted].localeCompare(b[sorted],doclang);
					} // vglFkt_s

					var vglFkt_n = function(a, b) {
						return a[sorted] - b[sorted];
					} // vglFkt_n

					var tsort = function(sp) { // Der Sortierer
						if (sp == sorted) { // Tabelle ist schon nach dieser Spalte sortiert, also nur Reihenfolge umdrehen
							arr.reverse();
							sortbuttons[sp].classList.toggle("sortedasc"); 
							sortbuttons[sp].classList.toggle("sorteddesc"); 
							tabletitel[sp].abbr = (tabletitel[sp].abbr==sort_info.asc)?sort_info.desc:sort_info.asc;
						}
						else { // Sortieren 
							if (sorted > -1) {
								sortbuttons[sorted].classList.remove("sortedasc");
								sortbuttons[sorted].classList.remove("sorteddesc");
								sortbuttons[sorted].classList.add("unsorted");
								tabletitel[sorted].abbr = "";
							}
							sortbuttons[sp].classList.remove("unsorted");
							sortbuttons[sp].classList.add("sortedasc");
							sorted = sp;
							tabletitel[sp].abbr = sort_info.asc;
							if(sorttype[sp] == "n") arr.sort(vglFkt_n);
							else arr.sort(vglFkt_s);
						}	
						for (var r = 0; r < nrows; r++) tbdy.appendChild(arr[r][ncols]); // Sortierte Daten zurückschreiben
					} // tsort
		
					// Kopfzeile vorbereiten
					for (var i = 0; i < tabletitel.length; i++) initTableHead(i);
		
					// Array mit Info, wie Spalte zu sortieren ist, vorbelegen
					for (var c = 0; c < ncols; c++) sorttype[c] = "n";
		
					// Tabelleninhalt in ein Array kopieren
					for (var r = 0; r < nrows; r++) {
						arr[r] = [];
						for (var c = 0; c < ncols; c++) {
							var cc = getData(tr[r].cells[c],c);
							arr[r][c] = cc;
							// tr[r].cells[c].innerHTML += "<br>"+cc+"<br>"+sorttype[c]; // zum Debuggen
						}
						arr[r][ncols] = tr[r];
					}
		
					// Tabelle die Klasse "is_sortable" geben
					tab.classList.add("is_sortable");

					// An caption Hinweis anhängen
					var caption = tab.caption;
					/* don't use this text, I can be modified by modifying table's caption
					if(caption) caption.innerHTML += doclang=="de"?
						"<br><span><small>Ein Klick auf die Spaltenüberschrift sortiert die Tabelle.</small></span>":
						"<br><span><small>A click on the column header sorts the table.</small></span>"
						*/
	
				} // tableSort

				// Alle Tabellen suchen, die sortiert werden sollen, und den Tabellensortierer starten.
				var initTableSort = function() { 
					var sort_Table = document.querySelectorAll("table.sortierbar");
					for (var i = 0; i < sort_Table.length; i++) new tableSort(sort_Table[i]);
				} // initTable

				if (window.addEventListener) window.addEventListener("DOMContentLoaded", initTableSort, false); // nicht im IE8

			})();
