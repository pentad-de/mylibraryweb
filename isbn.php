<?php
class isbn
{
	public static function genchksum13($isbn)
	{
	   $isbn = trim($isbn);
	   $tb = 0;
	   for ($i = 0; $i <= 12; $i++)
	   {
	      $tc = substr($isbn, -1, 1);
	      $isbn = substr($isbn, 0, -1);
		  if (!is_numeric($tc))
	      	return 0;
		  $ta = ($tc*3);
	      $tci = substr($isbn, -1, 1);
	      $isbn = substr($isbn, 0, -1);
		  if (!is_numeric($tci))
	      	return 0;
	      $tb = $tb + $ta + $tci;
	   }
	   
	   $tg = ($tb / 10);
	   $tint = intval($tg);
	   if ($tint == $tg) { return 0; }
	   $ts = substr($tg, -1, 1);
	   $tsum = (10 - $ts);
	   return $tsum;
	}
	public static function isbn10_to_13($isbn)
	{
		$isbn = str_replace('-', '', $isbn);
	   $isbn = trim($isbn);
	   if(strlen($isbn) == 12){ // if number is UPC just add zero
	      $isbn_13 = '0'.$isbn;}
	   else
	   {
	      $isbn2 = substr("978" . trim($isbn), 0, -1);
	      $sum13 = isbn::genchksum13($isbn2);
	      $isbn_13 = "$isbn2$sum13";
	   }
	   return ($isbn_13);
	}
		
		public static function isGermanIsbn($isbnToCheck)
		{
			if (strlen($isbnToCheck) ==  10)
			{
				return (substr($isbnToCheck, 0, 1) === "3");
			}
			if (strlen($isbnToCheck) ==  13)
			{
				return (substr($isbnToCheck, 0, 4) === "9783");
			}
			return (false);
		}
	}
?>