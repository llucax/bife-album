# vim: set noexpandtab tabstop=4 softtabstop=4 shiftwidth=4:
# +--------------------------------------------------------------------+
# |                       BIFE - Buil It FastEr                        |
# +--------------------------------------------------------------------+
# | This file is part of BIFE.                                         |
# |                                                                    |
# | BIFE is free software; you can redistribute it and/or modify it    |
# | under the terms of the GNU General Public License as published by  |
# | the Free Software Foundation; either version 2 of the License, or  |
# | (at your option) any later version.                                |
# |                                                                    |
# | BIFE is distributed in the hope that it will be useful, but        |
# | WITHOUT ANY WARRANTY; without even the implied warranty of         |
# | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU   |
# | General Public License for more details.                           |
# |                                                                    |
# | You should have received a copy of the GNU General Public License  |
# | along with Hooks; if not, write to the Free Software Foundation,   |
# | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA      |
# +--------------------------------------------------------------------+
# | Created: Mon May 19 00:16:56 ART 2003                              |
# | Authors: Leandro Lucarella <luca@lugmen.org.ar>                    |
# +--------------------------------------------------------------------+
#
# $Id$
#

MODULE=album

PHP_FILES=$(subst ./,,$(shell find src -name '*.php'))
EXAMPLE_FILES=$(subst ./,,$(shell find examples -regex '.*\.svn.*'))
DOC_FILES=

package: package.xml $(PHP_FILES) $(EXAMPLE_FILES) $(DOC_FILES)
	pear package

code: $(MODULE).xmi xmi2code.config
	@xmi2code

code-clean:
	@find src -name '*.bak' | xargs rm -vf
