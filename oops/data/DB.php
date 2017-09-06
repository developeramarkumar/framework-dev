<?php
namespace data;

class DB 
{
	private $table=null, 
			$whare=null,
			$select=null,
			$join=null,
			$leftJoin=null,
			$insert=null,
			$paginate=false,
			$pagination=null,
			$count=0,
			$connection=null;
	function __construct($table){
		$this->table=$table;
	}
	public static function table ($table)
	{
		 $s = new DB($table);
        return $s;
	}
	// public function setTable($table)
	// {
	// 	$this->table=$table;
	// 	return $this;
	// }
	public function where($arg)
	{
		$this->table=$table;
		return $this;
	}
	public function join($arg)
	{
		$this->table=$table;
		return $this;
	}
	public function insert($arg)
	{
		$this->table=$table;
		return $this;
	}
	public function paginate($arg)
	{
		$this->table=$table;
		return $this;
	}
	public function get($arg=null)
	{
		return $this->table;
	}
	public function first()
	{
		return $this->table;
	}
	public function query()
	{
		// return $this->table;
	}
	public function excute()
	{
		return $this->table;
	}

}	