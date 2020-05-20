<?php

namespace App\Models\Youtube;


abstract class DownloadTypes
{
    public const __default = self::VIDEO;

    public const VIDEO = 'video';

    public const AUDIO = 'audio';

    public const THUMBNAIL = 'thumbnail';
}
