<?php
// vim: set expandtab tabstop=4 shiftwidth=4 binary:
// +--------------------------------------------------------------------+
// |                       BIFE - Buil It FastEr                        |
// +--------------------------------------------------------------------+
// | This file is part of BIFE.                                         |
// |                                                                    |
// | BIFE is free software; you can redistribute it and/or modify it    |
// | under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation; either version 2 of the License, or  |
// | (at your option) any later version.                                |
// |                                                                    |
// | BIFE is distributed in the hope that it will be useful, but        |
// | WITHOUT ANY WARRANTY; without even the implied warranty of         |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU   |
// | General Public License for more details.                           |
// |                                                                    |
// | You should have received a copy of the GNU General Public License  |
// | along with Hooks; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA      |
// +--------------------------------------------------------------------+
// | Created: Wed May 17 18:16:54 ART 2003                              |
// | Authors: Leandro Lucarella <luca@lugmen.org.ar>                    |
// +--------------------------------------------------------------------+
//
// $Id$
//

// +X2C includes
require_once 'BIFE/Widget.php';
// ~X2C

require_once 'BIFE/Link.php';
require_once 'Image/Transform.php';

// +X2C Class 20 :Thumbs
/**
 * Photo album widget. [TODO: Make a better explanation]
 *
 * @package BIFE_Album
 * @access public
 */
class BIFE_Album_Thumbs extends BIFE_Widget {
    // ~X2C

    // +X2C Operation 22
    /**
     * Constructor.
     *
     * @param  array $attrs Attributes.
     *
     * @return void
     * @access public
     */
    function BIFE_Album_Thumbs($attrs) // ~X2C
    {
        $this->__construct($attrs);
    }
    // -X2C

    // +X2C Operation 57
    /**
     * Constructor.
     *
     * @param  array $attrs Attributes.
     *
     * @return void
     * @access public
     */
    function __construct($attrs) // ~X2C
    {
        // TODO - get defaults from an INI file.
        $defaults = array(
            'DIR'           => '.',
            'RECURSIVE'     => true,
            'THUMBSFORMAT'  => 'jpeg',
            'THUMBSDIR'     => '.thumbs',
            'EXTENSIONS'    => 'png,jpg,jpeg,PNG,JPG,JPEG',
            'MAXROWS'       => 0,
            'COLUMNS'       => 4,
            'LINK'          => 'photo.xbf',
        );
        $attrs = array_merge($defaults, $attrs);
        $attrs['EXTENSIONS'] = explode(',', $attrs['EXTENSIONS']);
        parent::__construct($attrs);
    }
    // -X2C

    // +X2C Operation 23
    /**
     * Renders the widget.
     *
     * @param  HTML_Template_HIT &$template Template to use to render the widget.
     *
     * @return string
     * @access public
     */
    function render(&$template) // ~X2C
    {
        $template->pushGroup('album');
        $list = $this->getList(BIFE_Link::getFsPath() . $this->attrs['DIR'],
                $this->attrs['EXTENSIONS'], $this->attrs['THUMBSFORMAT'],
                $this->attrs['THUMBSDIR']);
        $tot  = count($list);
        $rows = ceil($tot / $this->attrs['COLUMNS']);
        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $this->attrs['COLUMNS']; $col++) {
                $cur = $row * $this->attrs['COLUMNS'] + $col;
                if ($photo = @$list[$cur]) {
                    if (is_null($photo['THUMB'])) {
                        $photo['THUMB'] = $this->makeThumb($photo['FILE'],
                            $this->attrs['THUMBSFORMAT'],
                            $this->attrs['THUMBSDIR'],
                            BIFE_Link::getFsPath() . $this->attrs['DIR']);
                    }
                    $photo['THUMB'] = BIFE_Link::getWebPath()
                        . $this->attrs['DIR'] . '/' . $photo['THUMB'];
                    $photo['URL'] = BIFE_Link::getURL(
                        array(
                            'URL'                   => $this->attrs['LINK'],
                            'DATA-BIFE_ALBUM_FILE'  => BIFE_Link::getWebPath()
                                 . $this->attrs['DIR'] . '/' . $photo['FILE'],
                        )
                    );
                    $cell = $template->parse('item', $photo);
                } else {
                    $cell = $template->parse('empty');
                }
                $template->parseBuffered('cell', 'CONTENTS', $cell);
            }
            $template->parseBuffered('row', 'CONTENTS',
                $template->popBuffer('cell'));
        }
        $out = $template->parse('body', array(
            'DESC'     => $this->getDescription(),
            'CONTENTS' => $template->popBuffer('row')));
        $template->popGroup();
        return $out;
    }
    // -X2C

    // +X2C Operation 95
    /**
     * Gets a list of photos with their descriptions and thumbnails.
Returns an array of associative arrays with this keys:
<ul>
<li><b>file:</b> Photo filename.</li>
<li><b>desc:</b> Photo Description.</li>
<li><b>thumb:</b> Photo thumbnail filename.</li>
</ul>
     *
     * @param  string $root Directory where images are.
     * @param  array $exts Images extensions to accept.
     * @param  string $format Format to use to save the thumbs.
     * @param  string $thumbsdir Directory where the thumbs are.
     *
     * @return array
     * @access protected
     */
    function getList($root, $exts, $format, $thumbsdir) // ~X2C
    {
        $return = array();
        $d = dir($root);
        if ($d) {
            while (($file = $d->read()) !== false) {
                list($path, $name, $ext) = $this->splitFilename("$root/$file");
                if (is_readable("$root/$file") and in_array($ext, $exts)) {
                    $thumb = $this->getThumbFilename($file, $format,
                        $thumbsdir, $root);
                    $return[] = array(
                        'FILE'  => $file,
                        'DESC'  => $name,
                        'THUMB' => $thumb,
                    );
                }		
            }
            $d->close();
        }
        return $return;
    }
    // -X2C

    // +X2C Operation 97
    /**
     * Creates an image thumbnail, returning his filename.
     *
     * @param  string $filename Filename of the image to create the thumb.
     * @param  string $format Thumbs format.
     * @param  string $thumbsdir Directory where to put the thumbs.
     * @param  string $root Root directory.
     * @param  int $size Maximum thumbnail size.
     *
     * @return string
     * @access protected
     * @static
     */
    function makeThumb($filename, $format, $thumbsdir, $root, $size = 100) // ~X2C
    {
        list($path, $name, $ext) = $this->splitFilename($filename);
        $img =& Image_Transform::factory('GD');
        $img->load("$root/$filename");
        // If image is larger than the maximum size, we resize it.
        if ($img->img_x > $size or $img->img_y > $size ) {
            if (!@is_dir("$root/$thumbsdir") and !@mkdir("$root/$thumbsdir")) {
                return null;
            }
            if (PEAR::isError($img)) {
                return null;
            }
            if (!$img->scale($size)) {
                return null;
            }
        }
        $img->save("$root/$thumbsdir/$name.$format", $format);
        $img->free();
        return "$thumbsdir/$name.$format";
    }
    // -X2C

    // +X2C Operation 98
    /**
     * Returns the filename of an image thumb.
     *
     * @param  string $filename Filename of the image to get the thumb name.
     * @param  string $format Thumbs format.
     * @param  string $thumbsdir Directory where the thumbs are.
     * @param  string $root Root directory.
     *
     * @return string
     * @access protected
     */
    function getThumbFilename($filename, $format, $thumbsdir, $root) // ~X2C
    {
        list($path, $name, $ext) = $this->splitFilename($filename);
        if (is_readable("$root/$thumbsdir/$name.$format")) {
            return "$thumbsdir/$name.$format";
        } else {
            return null;
        }
    }
    // -X2C

    // +X2C Operation 102
    /**
     * Returns the description of the album.
     *
     * @return string
     * @access protected
     */
    function getDescription() // ~X2C
    {
        $root = $this->attrs['DIR'];
        return @join('', file($file));
    }
    // -X2C

    // +X2C Operation 100
    /**
     * Splits a filename returning an array with the path, name and extension.
     *
     * @param  string $filename Filename to split.
     *
     * @return array
     * @access public
     * @static
     */
    function splitFilename($filename) // ~X2C
    {
        $path = explode('/', $filename);
        $file = array_pop($path);
        $ext  = '';
        if (strstr($file, '.')) {
            preg_match('|([^/]+?)(\.([^\.]*))?$|', $file, $m);
            $file = @$m[1] . ((@$m[2] == '.' ) ? '.' : '');
            $ext  = @$m[3];
        }
        $dir = count($path) ? join('/', $path) : '';
        return array($dir, $file, $ext);
    }
    // -X2C

} // -X2C Class :Thumbs

?>