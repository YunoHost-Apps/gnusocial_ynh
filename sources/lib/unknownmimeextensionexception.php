<?php
if (!defined('GNUSOCIAL')) { exit(1); }

/**
 * Class for unknown MIME extension exception
 *
 * Thrown when we don't know the file extension for a given MIME type.
 * This generally means that all files are accepted since if we have
 * a list of known MIMEs then they have extensions coupled to them.
 *
 * @category Exception
 * @package  GNUsocial
 * @author   Mikael Nordfeldth <mmn@hethane.se>
 * @license  https://www.gnu.org/licenses/agpl-3.0.html
 * @link     https://gnu.io/social
 */

class UnknownMimeExtensionException extends ServerException
{
    public function __construct($mimetype)
    {
        // TRANS: We accept the file type (we probably just accept all files)
        // TRANS: but don't know the file extension for it.
        $msg = sprintf(_('Supported mimetype but unknown extension relation: %1$s'), _ve($mimetype));
        parent::__construct($msg);
    }
}
