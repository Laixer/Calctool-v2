<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

use \Auth;

class Resource extends Model {

	protected $table = 'resource';
	protected $guarded = array('id');

	public function user() {
		return $this->hasOne('User');
	}

	public function project() {
		return $this->hasOne('Project');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}

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
