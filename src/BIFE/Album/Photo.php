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
// | Created: Sat May 24 00:54:15 2003                                  |
// | Authors: Leandro Lucarella <luca@lugmen.org.ar>                    |
// +--------------------------------------------------------------------+
//
// $Id$
//

// +X2C includes
require_once 'BIFE/Widget.php';
// ~X2C

// +X2C Class 103 :Photo
/**
 * Photo widget.
 *
 * @package BIFE_Album
 * @access public
 */
class BIFE_Album_Photo extends BIFE_Widget {
    // ~X2C

    // +X2C Operation 108
    /**
     * Renders the widget using a template returning a string with the results.
     *
     * @param  HTML_Template_HIT &$template Template to use to render the widget.
     *
     * @return string
     * @access public
     */
    function render(&$template) // ~X2C
    {
        $attrs['FILE'] = @$this->attrs['FILE'] ? $this->attrs['FILE'] : @$_REQUEST['BIFE_ALBUM_FILE'];
        $attrs['DESC'] = @$this->attrs['DESC'] ? $this->attrs['DESC'] : basename($attrs['FILE']);
        $out = $template->parse('photo', $attrs, '', 'album');
        return $out;
    }
    // -X2C

} // -X2C Class :Photo

?>