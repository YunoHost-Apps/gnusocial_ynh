<?php
if (!defined('GNUSOCIAL')) { exit(1); }

/**
 * Class for unknown extension MIME type exception
 *
 * Thrown when we don't know the MIME type for a given file extension.
 *
 * @category Exception
 * @package  GNUsocial
 * @author   Mikael Nordfeldth <mmn@hethane.se>
 * @license  https://www.gnu.org/licenses/agpl-3.0.html
 * @link     https://gnu.io/social
 */

class UnknownExtensionMimeException extends ServerException
{
    public function __construct($ext)
    {
        // TRANS: We accept the file type (we probably just accept all files)
        // TRANS: but don't know the file extension for it. %1$s is the extension.
        $msg = sprintf(_('Unknown MIME type for file extension: %1$s'), _ve($ext));

        parent::__construct($msg);
    }
}
