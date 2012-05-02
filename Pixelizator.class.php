<?php

////////////////////////////////////////////////////////////////////////////////
///
///Coded By : Ayoub DARDORY (Apolikamixitos)
///Email : AYOUBUTO@Gmail.com
///Description : Visual encryption for image files
///Follow me : http://www.twitter.com/Apolikamixitos
///GitHub: http://github.com/apolikamixitos
//
////////////////////////////////////////////////////////////////////////////////

class Pixelizator {

    private $Puzzles;
    private $Image;
    private $HEIGHT;
    private $WIDTH;
    private $Slices;
    private $Switches;
    private $ReverseColor;

    public function __construct($ImageFile, $Slices = 1, $ReverseColor = false) {
        $this->Image = $this->Load($ImageFile);
        $this->WIDTH = imagesx($this->Image);
        $this->HEIGHT = imagesy($this->Image);
        $this->Slices = $Slices;
        $this->ReverseColor = $ReverseColor;
        $this->Switches = array();
    }

    private function Load($Filename) {
        return imagecreatefromjpeg($Filename);
    }

    private function Slice() {

        $x = $this->WIDTH;
        $y = $this->HEIGHT;

        $margex = round($x / $this->Slices);
        $margey = round($y / $this->Slices);

        $k = 0;
        for ($i = 0; $i < $this->Slices; $i++) {
            for ($j = 0; $j < $this->Slices; $j++) {
                if ((($j + 1) * $margey) < $y)
                    $this->Puzzles[$k]['y'] = array('start' => ($j * $margey), 'end' => ($j + 1) * $margey);
                else
                    $this->Puzzles[$k]['y'] = array('start' => ($j * $margey), 'end' => $y);


                if ((($i + 1) * $margex) < $x)
                    $this->Puzzles[$k]['x'] = array('start' => ($i * $margex), 'end' => ($i + 1) * $margex);
                else
                    $this->Puzzles[$k]['x'] = array('start' => ($i * $margex), 'end' => $x);


                $k++;
            }
        }
    }

    public function SwitchPuzzle($part0, $part1, $reversecolor = false) {
        $tmp0 = array();
//GET COLORS OF EACH PIXEL OF THIS PART 0
        for ($i = $part0['x']['start']; $i < $part0['x']['end']; $i++) {
            for ($j = $part0['y']['start']; $j < $part0['y']['end']; $j++) {
                $rgb = imagecolorat($this->Image, $i, $j);
                $colors = imagecolorsforindex($this->Image, $rgb);
                $red = $colors['red'];
                $green = $colors['green'];
                $blue = $colors['blue'];

                if ($reversecolor == true) {
                    $red = 255 - $red;
                    $green = 255 - $green;
                    $blue = 255 - $blue;
                }


                $tmp0[] = imagecolorallocate($this->Image, $red, $green, $blue);
            }
        }

        $tmp1 = array();
//GET COLORS OF EACH PIXEL OF THIS PART 0
        for ($i = $part1['x']['start']; $i < $part1['x']['end']; $i++) {
            for ($j = $part1['y']['start']; $j < $part1['y']['end']; $j++) {
                $rgb = imagecolorat($this->Image, $i, $j);
                $colors = imagecolorsforindex($this->Image, $rgb);
                $red = $colors['red'];
                $green = $colors['green'];
                $blue = $colors['blue'];

                if ($reversecolor == true) {
                    $red = 255 - $red;
                    $green = 255 - $green;
                    $blue = 255 - $blue;
                }

                $tmp1[] = imagecolorallocate($this->Image, $red, $green, $blue);
            }
        }

//REWRITE THOSE PIXELS WITH THE PIXELS OF PART 1
        $k = 0;
        for ($i = $part0['x']['start']; $i < $part0['x']['end']; $i++) {
            for ($j = $part0['y']['start']; $j < $part0['y']['end']; $j++) {
                imagesetpixel($this->Image, $i, $j, $tmp1[$k]);
                $k++;
            }
        }
//REWRITE THOSE PIXELS WITH THE PIXELS OF PART 1
        $k = 0;
        for ($i = $part1['x']['start']; $i < $part1['x']['end']; $i++) {
            for ($j = $part1['y']['start']; $j < $part1['y']['end']; $j++) {
                imagesetpixel($this->Image, $i, $j, $tmp0[$k]);
                $k++;
            }
        }
    }

    /*
     * Needs also some developpement.
     * 
     * All your contributions are welcomed for this project.
     * http://github.com/apolikamixitos
     * 
     */

    public function Encrypt($MaxRandSwitch) {
        //Slices the image to small puzzle parts
        $this->Slice();

        //Random switches
        $this->Switches = array();
        for ($i = 0; $i < $MaxRandSwitch; $i++) {
            $MAX = ($this->Slices * $this->Slices) - 1;
            $A = mt_rand(0, $MAX);
            $B = mt_rand(0, $MAX);
            $this->Switches[] = array('a' => $A, 'b' => $B);
            $DiffAX = $this->Puzzles[$A]['x']['end'] - $this->Puzzles[$A]['x']['start'];
            $DiffAY = $this->Puzzles[$A]['y']['end'] - $this->Puzzles[$A]['y']['start'];
            $DiffBX = $this->Puzzles[$B]['x']['end'] - $this->Puzzles[$B]['x']['start'];
            $DiffBY = $this->Puzzles[$B]['y']['end'] - $this->Puzzles[$B]['y']['start'];

            //Check if the puzzles parts have the same dimensions to switch. Needed for the border puzzle parts
            if ($DiffAX == $DiffBX && $DiffAY == $DiffBY)
                $this->SwitchPuzzle($this->Puzzles[$A], $this->Puzzles[$B], $this->ReverseColor);
        }
        $this->Switches = array_reverse($this->Switches, true); //Reverse the switches for decryption
    }

    public function Decrypt() {
        //Slices the image to small puzzle parts
        $this->Slice();
        foreach ($this->Switches as $Change) {
            $A = $Change['a'];
            $B = $Change['b'];
            $DiffAX = $this->Puzzles[$A]['x']['end'] - $this->Puzzles[$A]['x']['start'];
            $DiffAY = $this->Puzzles[$A]['y']['end'] - $this->Puzzles[$A]['y']['start'];
            $DiffBX = $this->Puzzles[$B]['x']['end'] - $this->Puzzles[$B]['x']['start'];
            $DiffBY = $this->Puzzles[$B]['y']['end'] - $this->Puzzles[$B]['y']['start'];

            if ($DiffAX == $DiffBX && $DiffAY == $DiffBY)
                $this->SwitchPuzzle($this->Puzzles[$B], $this->Puzzles[$A], $this->ReverseColor);
        }
    }

    public function SaveImage($filename) {
        imagejpeg($this->Image, $filename, 100);
    }

    /*
     * This method needs more developpement (encryption, compression, etc ...)
     * It generates the information about all the puzzle switchs
     * 
     * All your contributions are welcomed for this project.
     * http://github.com/apolikamixitos
     * 
     */

    public function GenerateKey($filename) {
        $f = fopen($filename, "w+");
        fputs($f, json_encode(
                        array_merge(
                                array('Slices' => $this->Slices), array('ReverseColor' => $this->ReverseColor), $this->Switches
                        )
                ));
        fclose($f);
    }

    public function LoadKey($filename) {
        $this->Switches = json_decode(file_get_contents($filename), true);
        $this->Slices = $this->Switches['Slices'];
        $this->ReverseColor = $this->Switches['ReverseColor'];
        unset($this->Switches['Slices']);
        unset($this->Switches['ReverseColor']);
    }

    public function Show() {
        header('Content-Type: image/jpg  

        

        ');
        imagejpeg($this->Image, NULL, 100);
    }

    public function setMaxRandSwitch($MaxRandSwitch) {
        $this->MaxRandSwitch = $MaxRandSwitch;
    }

    public function getPuzzles() {
        return $this->Puzzles;
    }

    public function getMaxRandSwitch() {
        return $this->MaxRandSwitch;
    }

    public function getSlices() {
        return $this->Slices;
    }

    public function setSlices($Slices) {
        $this->Slices = $Slices;
    }

}

?>
