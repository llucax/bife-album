<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
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

// Inicialization {{{
ini_set('include_path', '../src:../../hit/src:../../bife/src:../../base/src:'.
    ini_get('include_path'));
umask('002');
require_once 'HTML/Template/HIT.php';
require_once 'BIFE/Parser.php';
require_once 'BIFE/Translate.php';
// }}}

// Selects the file to view {{{
$file = isset($_REQUEST['BIFE']) ? $_REQUEST['BIFE'] : 'index.xbf';
#$file = isset($_SERVER['PATH_INFO']) ? ".{$_SERVER['PATH_INFO']}" : 'index.xbf';
// }}}

// Looks if we want to show the source {{{
if (@$_REQUEST['S']) {
    if (@$_REQUEST['B']) {
        // We want to see the BIFE file source.
        echo '<PRE>';
        echo htmlentities(join('', file($file)));
    } else {
        // We want to see the php file source.
        highlight_file($_SERVER['SCRIPT_FILENAME']);
    }
    exit;
}
// }}}

// If we are not looking at the source, we use BIFE to show the page {{{
$template =& new HTML_Template_HIT('templates');
$parser   =& new BIFE_Parser('BIFE_Translate');
$page     =& $parser->parseFile($file);
$parser->__destruct();
echo $page->render($template);
// }}}

?>
