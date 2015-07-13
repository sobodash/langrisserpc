#!/usr/bin/php -q
<?
//
// langpcdump v1.5 for PHP console mode
// (c) D 2002,2003
//
// This is a dumper for the Langrisser I PC .RES files. They are a simple
// resource format used by the game.
//
// Updates to 1.5
//   Added symbols dumping to the script output. Since the Korean is still
//   mangled byt he shifting table, this probably won't help you to read the
//   dumps. The reason for symbol dumping is to make it easier to match
//   the dumps to the Japanese scripts.
//   Because of the symbol dumping, this is no longer suitable for dumping
//   non-script .res files. Please use langpcresdump for this from now on.
//

echo ("\nlangpcdump v1.0 (c) D 2003\n");
set_time_limit(6000000);

if ($argc < 2) { DisplayOptions(); die; }
else { $infile = $argv[1]; }

$fd = fopen ($infile, "rb");

//Begin Character Class pointers
print "Reading in pointers...\n";
fseek($fd, 0x58a30, SEEK_SET);
$k=0;
for($i=0; $i<255; $i++) {
	$index[$k] = hexdec(bin2hex(strrev(fread($fd, 4)))) - 0x401000;
	$k++;
}
print "Dumping strings...\n";
for($i=0; $i<count($index); $i++) {
	fseek($fd, $index[$i], SEEK_SET);
	$string[$i] = "";
	$charchar = fread($fd, 1);
	while($charchar != chr(0)) {
		$string[$i] .= $charchar;
		$charchar = fread($fd, 1);
	}
}
$output = "";
for($i=0; $i<count($string); $i++) {
	$output .= $string[$i] . "<>\r\n\r\n";
}
print "Writing class.txt...\n";
$fo = fopen("class.txt", "wb");
fputs($fo, $output);
fclose($fo);
// End Character Class pointers
unset($index, $output, $k, $charchar, $string, $i);

// Begin Character Name pointers
print "Reading in pointers...\n";
fseek($fd, 0x5922C, SEEK_SET);
$k=0;
for($i=0; $i<195; $i++) {
	$index[$k] = hexdec(bin2hex(strrev(fread($fd, 4)))) - 0x401000;
	$k++;
}
print "Dumping strings...\n";
for($i=0; $i<count($index); $i++) {
	print "  String $i...\n";
	fseek($fd, $index[$i], SEEK_SET);
	$string[$i] = "";
	$charchar = fread($fd, 1);
	while($charchar != chr(0)) {
		$string[$i] .= $charchar;
		$charchar = fread($fd, 1);
	}
}
$output = "";
for($i=0; $i<count($string); $i++) {
	$output .= $string[$i] . "<>\r\n\r\n";
}
print "Writing names.txt...\n";
$fo = fopen("names.txt", "wb");
fputs($fo, $output);
fclose($fo);
// End Character Name pointers

echo ("All done!...\n\n");

function DisplayOptions() {
	echo ("Dumps class/character strings from the Langrisser 1 PC executable\n  usage: langpcdump [input]\n\n");
}

?>
