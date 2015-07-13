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

// Classes have 1584 bytes max
$fd = fopen ("class.txt", "rb");
$fddump = fread($fd, filesize("class.txt"));
fclose($fd);

$class_out = array(""); $class_pointers = ""; $class_index = 0;
$class_arr = split("<>\r\n\r\n", $fddump); unset($fddump);
$inject_str = "";

for($i=0; $i<count($class_arr); $i++) {
	$index = array_search($class_arr[$i], $class_out);
	if($index === FALSE) {
		$class_out[$class_index] = $class_arr[$i];
		$inject_str .= $class_arr[$i] . chr(0);
		$class_pointers[$i] = $class_index;
		$class_index++;
	}
	else {
		$class_pointers[$i] = $index;
	}
}
//die (print strlen($inject_str));
$inject_ptr = "";
for($i=0; $i< count($class_arr) -1; $i++){
	print "$i\n";
	$inject_ptr .= pack("V*", strpos($inject_str, $class_arr[$i]) + 0x401000 + 0x58400);
}
$inject_str = str_pad($inject_str, 1584, chr(0), STR_PAD_RIGHT);

$fd = fopen ("langpcf3.exe", "rb");
$fddump = fread($fd, filesize("langpcf3.exe"));
fclose($fd);

$pt1 = substr($fddump, 0, 0x58400);
$pt2 = substr($fddump, 0x58e2c);

$fo = fopen("test.exe", "wb");
fputs($fo, $pt1 . $inject_str . $inject_ptr . $pt2);
fclose($fo);





// Names   have 1016 bytes max


echo ("All done!...\n\n");

function DisplayOptions() {
	echo ("Dumps class/character strings from the Langrisser 1 PC executable\n  usage: langpcdump [input]\n\n");
}

?>
