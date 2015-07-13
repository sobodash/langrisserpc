<?

mkdir("maps");

for($sc=1; $sc<108; $sc++) {
  $csc = str_pad($sc, 2, "0", STR_PAD_LEFT);
  //$maptiles = imagecreatefrompng("resmap\ST".$csc.".png");
  $maptiles = imagecreatefrompng("resmap\CELL".$csc.".png");

  $fd = fopen("resmap\LAND".$csc.".HF", "rb");
  $width = hexdec(bin2hex(strrev(fread($fd, 4))));
  $height = hexdec(bin2hex(strrev(fread($fd, 4))));
  fseek($fd, 0x10, SEEK_SET);
  $scenario = imagecreate($width*48, $height*48);
  $tiles = $width * $height;
  $dst_row = 0; $dst_col = 0;
  for($i=0; $i<$tiles; $i++) {
    //Get a tile byte
    $temp = bin2hex(fread($fd, 1));
    //Split this to nibbles, it's time to play Battleship(tm)
    $row = hexdec($temp[0]); $col = hexdec($temp[1]);
    //Grab a 48x48 square from the source map and plug it into our new image
    imagecopy($scenario, $maptiles, $dst_col*48, $dst_row*48, $col*48, $row*48, 48, 48);
    if(($dst_col+1)%$width==0) {
      $dst_row++; $dst_col=0;
    }
    else $dst_col++;
  }
  fclose($fd);
  imagepng($scenario, "maps\scenario".$csc.".png");
}

?>