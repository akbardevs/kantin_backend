<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use SoftDeletes;

	protected $table = 'fileUploads';

	protected $fillable = [
		'title',
		'path'
	];

	protected $dates = [
		'deleted_at'
	];
}
