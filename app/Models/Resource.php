<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Models;

use BynqIO\Dynq\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use Ownable;

    protected $table = 'resource';
    protected $guarded = array('id');

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function project() {
    //     return $this->hasOne('Project');
    // }

    public function extension() {
        return pathinfo($this->resource_name)['extension'];
    }

    public function fa_icon() {
        $ext = $this->extension();
        switch ($ext) {
            case 'csv':
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel-o';

            case 'doc':
            case 'docx':
            case 'odf':
            case 'odt':
            case 'ods':
                return 'fa-file-word-o';

            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint-o';

            case 'txt':
                return 'fa-file-text-o';

            case 'pdf':
                return 'fa-file-pdf-o';

            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'bmp':
                return 'fa-file-image-o';

            case 'zip':
            case 'tar':
            case 'gz':
            case 'rar':
            case '7z':
                return 'fa-file-archive-o';

            case 'wav':
            case 'mp3':
            case 'flac':
                return 'fa-file-audio-o';

            case 'mkv':
            case 'mp4':
            case 'avi':
                return 'fa-file-video-o';

            case 'php':
            case 'c':
            case 'h':
            case 'cpp':
            case 'cs':
            case 'js':
            case 'css':
            case 'html':
            case 'xml':
                return 'fa-file-code-o';

            default:
                return 'fa-file';
        }
    }
}
