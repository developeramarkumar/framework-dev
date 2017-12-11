<?php
namespace App\Model;
use \Illuminate\Database\Eloquent\Model;

class News extends Model
{
	protected $table = 'news';
	protected $fillable = ['id','title','description','type_news','status','created_at','updated_at','publish_date'];

	public function news(){
		return $this->belongsTo(News::class,'id','id');
	}

	
	
}
