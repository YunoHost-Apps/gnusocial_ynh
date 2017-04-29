<?php

if (!defined('GNUSOCIAL')) { exit(1); }

class FileNotStoredLocallyException extends ServerException
{
    public $file = null;

    public function __construct(File $file)
    {
        $this->file = $file;
        common_debug('Requested local URL for a file that is not stored locally with id=='._ve($this->file->getID()));
        parent::__construct(_('Requested local URL for a file that is not stored locally.'));
    }
}
