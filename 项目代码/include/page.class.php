<?php
if(!defined('IN_MO')){
	exit('Access Denied');
}
class page{
	
	//每页显示信息数
	protected $pagesize;
	//显示页码
	protected $pagenum;
	//当前页码
	protected $page;
	//总页数
	protected $pagezong;
	//总记录数
	protected $counter;
	 
	//设置各项参数
	public function setPage($counter=0,$pagesize=12,$pagenum=10){
		$this->counter = $counter; 
		$this->pagesize = $pagesize;
		$this->pagenum = $pagenum;
		$this->setpagezong(); 
		$this->setpageno();
	}
	
	//设置总页数
	protected function setpagezong(){
		$this->pagezong = ceil($this->counter / $this->pagesize);
	}
	
	//设置当前页
	protected function setpageno(){
		(isset($_GET['page'])) ? $page = (int)$_GET['page'] : $page = 1;
		$this->page = ($page<1) ? 1 : $page;
		$this->page = ($this->page>$this->pagezong) ? $this->pagezong : $this->page;
	}
	
	//设置查询限制
	public function getlimit(){
		$limit = ($this->page - 1) * $this->pagesize; 
		$limit = ($limit < 0) ? 0 : $limit;
		$limit .= ', ' . $this->pagesize;
		$limit = ' limit '.$limit;
		return $limit;
	}
	
	//获取分页菜单
	public function getpagemenu($pra = '', $type = 1 ,$home = '首页', $prev = '上一页', $next = '下一页', $last = '尾页'){
		if ($type == 1){
			$home = $this->gethomeurl($pra, $home); 
			$prev = $this->getprevurl($pra, $prev); 
			$next = $this->getnexturl($pra, $next);
			$last = $this->getlasturl($pra, $last);
			$menu = '第<font color="#ff0000">'.$this->page.'</font>页|共<font color="#ff0000">'.$this->pagezong.'</font>页|<font color="#ff0000">'.$this->pagesize.'</font>项/页|共<font color="#ff0000">'.$this->counter.'</font>项'.'　　　　　　['.$home.']['.$prev.']['.$next.']['.$last.']  <input type="text" name="page" id="page" maxlength="6" style="width:35px;BORDER: #aacbe1 1px solid; font-size:12px;height:18px;" value="'.$this->page.'"> <input type="button" name="buttonn" class="btn" value="转到" onclick="location=\'?page=\'+document.getElementById(\'page\').value" style="BORDER: #aacbe1 1px solid; font-size:12px;">';
		}elseif ($type == 2){
			$sha = $this->getshaurl($pra, $prev);
			$num = $this->getnumurl($pra);
			$xia = $this->getxiaurl($pra, $next);
			$menu = '<div class="pages"><ul>'.$sha.$num.$xia.'</ul></div>';
			$menu .= '<style type="text/css">
					.pages {color: #333;padding: 3px;font-family:Verdana;font-size:12px;font-weight:bold;line-height:15px;}
					.pages ul{list-style-type: none;margin:0px;padding:0px;}
					.pages li {float: left;display: inline;margin: 0 5px 0 0;display: block;}
					.pages li a {float: left;color: #88af3f;padding:1px 5px 2px 5px;border: 1px solid #ddd;text-decoration: none;background-color:#FFFFFF}
					.pages li a:hover {color: #638425;background: #f1ffd6;border: 1px solid #85bd1e;}
					.pages li.current {color: #FFF;border: 1px solid #b2e05d;padding:1px 5px 2px 5px;background: #b2e05d;}
					.pages li.nolink {color: #CCC;border: 1px solid #F0F0F0;padding:1px 5px 2px 5px;background-color:#FFFFFF}
					.p_clear{ clear:both;}
					</style>';
		}
		echo $menu;
	}
	
	//首页
	protected function gethomeurl($pra, $word){
		if($this->page <= 1){
			$home = $word;
		}else{
			$home = '<a href="?page=1'.$pra.'">'.$word.'</a>';
		}
		return $home;
	}
	
	//上一页
	protected function getprevurl($pra, $word){
		if($this->page <= 1){
			$prev = $word;
		}else{
			$prev = '<a href="?page='.($this->page - 1).$pra.'">'.$word.'</a>';
		}
		return $prev;
	}
	
	//下一页
	protected function getnexturl($pra, $word){
		if($this->page >= $this->pagezong){
			$next = $word;
		}else{
			$next = '<a href="?page='.($this->page + 1).$pra.'">'.$word.'</a>';
		}
		return $next;
	}
	
	//尾页
	protected function getlasturl($pra, $word){
		if($this->page >= $this->pagezong){
			$last = $word;
		}else{
			$last = '<a href="?page='.$this->pagezong.$pra.'">'.$word.'</a>';
		}
		return $last;
	}
	
	//数字上一页
	protected function getshaurl($pra, $word){
		if ($this->page!=1){
			$sha = '<li><a href="?page='.($this->page-1).$pra.'" >'.$word.'</a></li>';
		}else{
			$sha = '<li class="nolink">'.$word.'</li>';
		}
		return $sha;
	}
	
	//数字上一页
	protected function getxiaurl($pra, $word){
		if ($this->page!=$this->pagezong){
			$xia = '<li><a href="?page='.($this->page+1).$pra.'">'.$word.'</a></li>';
		}else{
			$xia = '<li class="nolink">'.$word.'</li>';
		}
		return $xia;
	}

	//数字页码
	protected function getnumurl($pra){
		if ($this->pagenum%2 == 0){
			$step2 = $this->pagenum / 2;
			$step1 = $step2 - 1;
		}else{
			$step1 = floor($this->pagenum / 2);
			$step2 = $step1;
		}
		$p_start = $this->page - $step1;
		$p_end = $this->page + $step2;
		$s_str = '<li><a href="?page=1'.$pra.'">1...</a></li>';
		$e_str = '<li><a href="?page='.$this->pagezong.$pra.'">...'.$this->pagezong.'</a></li>';
		if($this->pagezong <= $this->pagenum || $this->pagezong <= $step2){
			$p_start = 1;
			$p_end = $this->pagezong;
			$s_str = '';
			$e_str = '';
		}else{
			if($p_start <= 1){
				$s_str = '';
				$p_start = 1;
				$p_end = $this->pagenum;
			}
			if ($p_end >= $this->pagezong){
				$p_end = $this->pagezong;
				$p_start = $this->pagezong - $this->pagenum + 1;
				$e_str = '';
			}
		}
		$html = $s_str;
		for($i = $p_start;$i <= $p_end;$i++){
			if ($i == $this->page){
				$html .= '<li class="current">'.$i.'</li>';
			}else{
				$html .= '<li><a href="?page='.$i.$pra.'">'.$i.'</a>'.'</li>';
			}
		}
		$html .= $e_str;
		return $html;
	}
}
?>