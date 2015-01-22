<?php

/*
 * NekoGD PHPGD Library.
 * Image generation library for PHP.
 * MIME Compability
 * - PNG
 * - GIF
 * - JPEG
 */

class NekoGD
{

    private $product, $name, $height, $width, $mime;
    private $watermark, $w_mime;
    private $text = array();
    private $arc = array();
    private $compatibility = array('png', 'gif', 'jpeg');
    private $layers;

    /*
     * Check if PHPGD is loaded.
     */
    public function __construct()
    {
        if (!extension_loaded('gd') && !function_exists('gd_info')) {
            throw new Exception ('PHPGD Extention is not loaded!');
        }
    }

    /*
     * Configure your image. Function cannot be chained.
     * $type available in png, gif and jpeg
     */
    public function config($type, $height = "0", $width = "0", $name = "image")
    {
        $this->height = $height;
        $this->width = $width;
        $this->name = $name;

        if (in_array(strtolower($type), $this->compatibility)) {
            $this->mime = strtolower($type);
            $this->image_name();
            return header('Content-type: image/' . strtolower($type));
        }

    }

    /*
     * Generating the image background. Functions can be chained.
     * $this->bgi() Generates background with a pre-made base image.
     *   - PNG mimes only work on PNG images.
     *   - GIF mimes only work on GIF images.
     *   - JPEG mimes only work on JPEG images.
     * $this->bgc() Generate background with the specified background color.
     *   - $color as in the background color.
     * $this->bgt() Generate a transparent background.
     */
    public function bgi($image_url)
    {
        $this->product = $this->img_create($this->mime, $image_url);
        return $this;
    }

    public function bgc($color = "ffffff")
    {
        $this->product = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->product, '0x' . substr($color, 0, -4), '0x' . substr($color, 2, -2), '0x' . substr($color, 4));
        imagefilledrectangle($this->product, 0, 0, $this->width, $this->height, $color);
        return $this;
    }

    public function bgt()
    {
        $this->product = imagecreatetruecolor($this->width, $this->height);
        $transparent = imagecolorallocate($this->product, 255, 255, 254);
        imagefill($this->product, 0, 0, $transparent);
        imagecolortransparent($this->product, $transparent);
        return $this;
    }

    /*
     * Text on images.
     * $this->text() Setting the configuration for text.
     *   - $ttf font file (.ttf format).
     *   - $color as in the 6-digit hexaecimal color code.
     *   - $angle as in the degrees. Can be left blank for 0 value.
     * $this->write() Writing text on the image with the set configuration. Function can be chained.
     *   - $text as in the text content.
     *   - $position as in pos(x,y). Can be left blank for 0 value.
     */
    public function text($ttf, $color, $font_size, $angle = "0")
    {
        if (file_exists($ttf)) {
            $this->text['ttf'] = $ttf;
        } else {
            throw new Exception ('Font file does not exist!');
        }
        $this->text['color'] = $color;
        $this->text['angle'] = $angle;
        $this->text['size'] = $font_size;
    }

    public function write($word, $position = "0,0")
    {
        $color = imagecolorallocate($this->product, '0x' . substr($this->text['color'], 0, -4), '0x' . substr($this->text['color'], 2, -2), '0x' . substr($this->text['color'], 4));
        $position = explode(',', $position);
        imagefttext($this->product, $this->text['size'], $this->text['angle'], $position['0'], $position['1'], $color, $this->text['ttf'], $word);
        return $this;
    }

    /*
     * Arc on images.
     * $this->arc() Setting the configuration for the arc.
     *   - $height as in the thickness of the arc.
     *   - $width as in the length of the arc.
     *   - $position as in the position of where the arc will be at.
     *   - $color as in the 6-digit hexaecimal color code.
     * $this->sketch() Drawing the arc.
     *   - $start as in the starting point of the arc.
     *   - $end as in the ending point of the arc.
     */
    public function arc($height, $width, $position = "0,0", $color)
    {
        $position = explode(',', $position);
        $this->arc['height'] = $height;
        $this->arc['width'] = $width;
        $this->arc['x'] = $position[0];
        $this->arc['y'] = $position[1];
    }

    public function sketch($start, $end)
    {
        $color = imagecolorallocate($this->product, '0x' . substr($this->text['color'], 0, -4), '0x' . substr($this->text['color'], 2, -2), '0x' . substr($this->text['color'], 4));
        imagearc($this->product, $this->arc['x'], $this->arc['y'], $this->arc['width'], $this->arc['height'], $start, $end, $color);
        return $this;
    }

    /*
     * Rotating the image.
     */
    public function rotate($degrees)
    {
        imagesavealpha($this->product, true);
        $pngTransparency = imagecolorallocatealpha($this->product, 0, 0, 0, 127);
        imagefill($this->product, 0, 0, $pngTransparency);
        $this->product = imagerotate($this->product, $degrees, $pngTransparency, 0);
        return $this;
    }

    /*
     * Watermark on image. Function can be chained.
     */
    public function watermark($image)
    {
        $image_ext = explode('.', $image);

        $this->watermark = $this->img_create($image_ext[1], $image);

        $product_width = imagesx($this->product);
        $product_height = imagesy($this->product);

        $watermark_width = imagesx($this->watermark);
        $watermark_height = imagesy($this->watermark);

        imagecopy(
            $this->product,
            $this->watermark,
            $product_width - $watermark_width, $product_height - $watermark_height,
            0, 0,
            $watermark_width, $watermark_height
        );

        imageDestroy($this->watermark);
        return $this;
    }

    /*
     * Gradient on image. Function can be chained.
     */
    public function gradient($placement = "0,0", $dimension = "0x0", $color = "ffffff,000000", $vertical = false)
    {
        $color = explode(',', $color);
        $dimension = explode('x', $dimension);
        $placement = explode(',', $placement);

        $dimension = array(
            'w' => $dimension[0],
            'h' => $dimension[1]
        );

        $placement = array(
            'x' => $placement[0],
            'y' => $placement[1]
        );

        $color_1 = array(
            'r' => hexdec(substr($color[0], 0, -4)),
            'g' => hexdec(substr($color[0], 2, -2)),
            'b' => hexdec(substr($color[0], 4))
        );
        $color_2 = array(
            'r' => hexdec(substr($color[1], 0, -4)),
            'g' => hexdec(substr($color[1], 2, -2)),
            'b' => hexdec(substr($color[1], 4))
        );

        $new = imagecreatetruecolor($dimension['w'], $dimension['h']);
        for ($i = 0; $i < $dimension['h']; $i++) {
            $color_r = floor($i * ($color_2['r'] - $color_1['r']) / $dimension['h']) + $color_1['r'];
            $color_g = floor($i * ($color_2['g'] - $color_1['g']) / $dimension['h']) + $color_1['g'];
            $color_b = floor($i * ($color_2['b'] - $color_1['b']) / $dimension['h']) + $color_1['b'];
            $color = imagecolorallocate($new, $color_r, $color_g, $color_b);

            //$dist       = imagecreatetruecolor($dimension['w'], $dimension['h']);
            //$dist_color = imagecolorallocate($dist, '0x00', '0x00', '0x00');
            //imagefill($dist, 0, 0, $dist_color);
            imageline($new, 0, $i, $dimension['w'], $i, $color);
        }

        if ($vertical) {
            $new = imagerotate($new, 90, 0);
            imagecopy(
                $this->product,
                $new,
                $placement['x'],
                $placement['y'],
                0,
                0,
                $dimension['w'],
                $dimension['h']
            );
        } else {
            imagecopy(
                $this->product,
                $new,
                $placement['x'],
                $placement['y'],
                0,
                0,
                $dimension['w'],
                $dimension['h']
            );
            imageDestroy($new);
        }
        return $this;
    }

    /*
     * Sharpen this image with a fixed matrix.
     * Recommended with use of $this->bgi().
     */
    public function sharpen()
    {
        $matrix = array(
            array(-1, -1, -1),
            array(-1, 16, -1),
            array(-1, -1, -1),
        );
        $divisor = array_sum(array_map('array_sum', $matrix));
        $offset = 0;
        imageconvolution($this->product, $matrix, $divisor, $offset);
        return $this;
    }

    /*
     * Output the image.
     */
    public function output()
    {
        if ($this->product) {

            switch ($this->mime) {
                case "png":
                    return imagepng($this->product);
                    break;
                case "gif":
                    return imagegif($this->product);
                    break;
                case "jpeg":
                    return imagejpeg($this->product);
                    break;
            }

        } else {

            return false;

        }
    }

    /*
     * Core Functions
     * $this->img_create() Core function to create a image dump from image files.
     * $this->image_name() Setting the name for the header details.
     */
    private function img_create($extension, $image)
    {
        switch ($extension) {
            case "png":
                return imagecreatefrompng($image);
                break;
            case "gif":
                return imagecreatefromgif($image);
                break;
            case "jpeg":
                return imagecreatefromjpeg($image);
                break;
        }
    }

    private function image_name()
    {
        return header('Content-Disposition: inline;filename=' . $this->name . '.' . $this->mime);
    }

    public function add_layer($name, $callback)
    {
        if (is_callable($callback)) {
            $this->layers[$name] = $callback();
        }
        return $this;
    }

    /*
     * Frees image memory.
     */
    public function __destruct()
    {
        if (!empty($this->product) and !empty($this->mime)) {
            imagedestroy($this->product);
        }
    }

}

?>