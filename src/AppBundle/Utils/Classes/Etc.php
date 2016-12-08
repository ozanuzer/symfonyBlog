<?php
// in AppBundle/Utils/Classes/Etc.php

namespace AppBundle\Utils\Classes;

class Etc {
    public function slug($text) {
       $text = str_replace ( array ("&#304;", "\u0130", "\xDD", "Ý", "İ" ), "I", $text );
       $text = str_replace ( array ("&#305;", "\u0131", "\xFD", "ý", "ı" ), "i", $text );
       $text = str_replace ( array ("&#286;", "\u011e", "\xD0", "Ð", "Ğ" ), "G", $text );
       $text = str_replace ( array ("&#287;", "\u011f", "\xF0", "ð", "ğ" ), "g", $text );
       $text = str_replace ( array ("&Uuml;", "\u00dc", "\xDC", "Ü" ), "U", $text );
       $text = str_replace ( array ("&uuml;", "\u00fc", "\xFC", "ü" ), "u", $text );
       $text = str_replace ( array ("&#350;", "\u015e", "\xDE", "Þ", "Ş" ), "S", $text );
       $text = str_replace ( array ("&#351;", "\u015f", "\xFE", "þ", "ş" ), "s", $text );
       $text = str_replace ( array ("&Ouml;", "\u00d6", "\xD6", "Ö" ), "O", $text );
       $text = str_replace ( array ("&ouml;", "\u00f6", "\xF6", "ö" ), "o", $text );
       $text = str_replace ( array ("&Ccedil;", "\u00c7", "\xC7", "Ç" ), "C", $text );
       $text = str_replace ( array ("&ccedil;", "\u00e7", "\xE7", "ç" ), "c", $text );
	   $text = str_replace ( array (".", "?", "!", "'" ), "", $text );
   	   $text = str_replace ( "*", "x", $text );
   	   $text = str_replace ( "&", "_", $text );
	   $text = str_replace ( array (" ", "chr(32)", "%20" ), "_", $text );
	   return $text;
	}
}
