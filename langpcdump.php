#!/usr/bin/php -q
<?php
//
// langpcdump v1.5 for PHP console mode
// (c) 2002, 2003 Derrick Sobodash
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

echo ("\nlangpcdump v1.0 (c) 2003 Derrick Sobodash\n");
set_time_limit(6000000);

if ($argc < 2) { DisplayOptions(); die; }
else { $infile = $argv[1]; }

$fd = fopen ($infile, "rb");
// Read in the first 4 bytes in little endian ordering to find out how
// many files are in the resource.
$count = hexdec(bin2hex(strrev(fread ($fd, 4))));
$s=0;

// Parse the header
for ($i=4; $i < ($count * 24); $i=$i+24) {
	$parse = fread ($fd, 24);
	// FILENAME = [16-bits] pad 0x00
	$file[$s] = substr($parse, 0, 16);
	// OFFSET = [4-Bits] little endian absolute addressing
	$pos[$s] = hexdec(bin2hex(strrev(substr($parse, 16, 4))));
	// SIZE = [4-Bits] little endian
	$len[$s] = hexdec(bin2hex(strrev(substr($parse, 20, 4))));
	$s++;
}
unset ($s, $i);
mkdir(substr($infile, 0, -4));

for ($i = 0; $i < $count; $i++) {
	print "Dumping $file[$i]... ";
	fseek ($fd, $pos[$i], SEEK_SET);
	$bin = fread($fd, $len[$i]);
	$bin = str_replace(chr(7), "?", $bin);
	$bin = str_replace(chr(22), ".", $bin);
	$bin = str_replace(chr(24), " ", $bin);
	$bin = str_replace(chr(25), "!", $bin);
	$bin = str_replace(chr(49), "<clsr>" . chr(13) . chr(10), $bin);
	$bin = str_replace(chr(50), chr(13) . chr(10), $bin);
	$bin = str_replace(chr(56), "<>" . chr(13) . chr(10) . chr(13) . chr(10), $bin);
	$bin = str_replace(chr(29) . chr(75), "<str1>", $bin);
	$bin = str_replace(chr(29) . chr(107), "<str2>", $bin);
	$fo = fopen(substr($infile, 0, -4) . "/$file[$i]", "w");
	fputs($fo, $bin);
	fclose($fo);
	print "done!\n";
}

fclose($fd);

echo ("All done!...\n\n");

function DisplayOptions() {
	echo ("Extracts files from Langrisser I resources to a folder of the same name\n  usage: langpcdump [input]\n\n");
}

?>
