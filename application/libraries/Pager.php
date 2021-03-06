<?php
/**
 * @author: shwdai@gmail.com
 */
class pager {
	
	public $rowCount = 0;
	public $pageNo = 1;
	public $pageSize = 15;
	public $pageCount = 0;
	public $offset = 0;
	public $pageString = 'page';
	
	private $script = null;
	private $valueArray = array ();
	
	public function __construct($count = 0, $size = 15, $string = 'page') {
		$this->defaultQuery ();
		$this->pageString = $string;
		$this->pageSize = abs ( $size );
		$this->rowCount = abs ( $count );
		
		$this->pageCount = ceil ( $this->rowCount / $this->pageSize );
		$this->pageCount = ($this->pageCount <= 0) ? 1 : $this->pageCount;
		$this->pageNo = abs ( intval ( @$_GET [$this->pageString] ) );
		$this->pageNo = $this->pageNo == 0 ? 1 : $this->pageNo;
		$this->pageNo = $this->pageNo > $this->pageCount ? $this->pageCount : $this->pageNo;
		$this->offset = ($this->pageNo - 1) * $this->pageSize;
	}
	
	private function genURL($param, $value) {
		$valueArray = $this->valueArray;
		$valueArray [$param] = $value;
		return $this->script . '?' . http_build_query ( $valueArray );
	}
	
	private function defaultQuery() {
		($script_uri = @$_SERVER ['SCRIPT_URI']) || ($script_uri = @$_SERVER ['REQUEST_URI']);
		$q_pos = strpos ( $script_uri, '?' );
		if ($q_pos > 0) {
			$qstring = substr ( $script_uri, $q_pos + 1 );
			parse_str ( $qstring, $valueArray );
			$script = substr ( $script_uri, 0, $q_pos );
		} else {
			$script = $script_uri;
			$valueArray = array ();
		}
		$this->valueArray = empty ( $valueArray ) ? array () : $valueArray;
		$this->script = $script;
	}
	
	public function paginate($switch = 1) {
		$from = $this->pageSize * ($this->pageNo - 1) + 1;
		$from = ($from > $this->rowCount) ? $this->rowCount : $from;
		$to = $this->pageNo * $this->pageSize;
		$to = ($to > $this->rowCount) ? $this->rowCount : $to;
		$size = $this->pageSize;
		$no = $this->pageNo;
		$max = $this->pageCount;
		$total = $this->rowCount;
		
		return array ('offset' => $this->offset, 'from' => $from, 'to' => $to, 'size' => $size, 'no' => $no, 'max' => $max, 'total' => $total );
	}
	
	public function GenWap() {
		$r = $this->paginate ();
		$pagestring = '<p align="right">';
		if ($this->pageNo > 1) {
			$pageString .= '4 <a href="' . $this->genURL ( $this->pageString, $this->pageNo - 1 ) . '" accesskey="4">上页</a>';
		}
		if ($this->pageNo > 1 && $this->pageNo < $this->pageCount) {
			$pageString .= '｜';
		}
		if ($this->pageNo < $this->pageCount) {
			$pageString .= '<a href="' . $this->genURL ( $this->pageString, $this->pageNo + 1 ) . '" accesskey="6">下页</a> 6';
		}
		$pageString .= '</p>';
		return $pageString;
	}
	
	public function GenBasic() {
		$r = $this->paginate ();
		$buffer = null;
		$index = lang('zyj_homepage');
		$pre = lang('zyj_pre');
		$next = lang('zyj_next');
		$last = lang('zyj_last');
		
		if ($this->pageCount <= 7) {
			$range = range ( 1, $this->pageCount );
		} else {
			$min = $this->pageNo - 3;
			$max = $this->pageNo + 3;
			if ($min < 1) {
				$max += (3 - $min);
				$min = 1;
			}
			if ($max > $this->pageCount) {
				$min -= ($max - $this->pageCount);
				$max = $this->pageCount;
			}
			$min = ($min > 1) ? $min : 1;
			$range = range ( $min, $max );
		}
		if ($this->rowCount < 1) {
			return null;
		}
		$buffer .= '<ul class="paginator">';
		//$buffer .= "<li>共{$this->rowCount}条</li>";
		if ($this->pageNo > 1) {
			$buffer .= "<li><a href='" . $this->genURL ( $this->pageString, 1 ) . "'>{$index}</a><li><a href='" . $this->genURL ( $this->pageString, $this->pageNo - 1 ) . "'>{$pre}</a>";
		}
		foreach ( $range as $one ) {
			if ($one == $this->pageNo) {
				$buffer .= "<li class=\"current\">{$one}</li>";
			} else {
				$buffer .= "<li><a href='" . $this->genURL ( $this->pageString, $one ) . "'>{$one}</a><li>";
			}
		}
		if ($this->pageNo < $this->pageCount) {
			$buffer .= "<li><a href='" . $this->genURL ( $this->pageString, $this->pageNo + 1 ) . "'>{$next}</a></li><li><a href='" . $this->genURL ( $this->pageString, $this->pageCount ) . "'>{$last}</a></li>";
		}
		return $buffer . "<li>".lang('zyj_g')." {$this->rowCount} ".lang('zyj_tiao')."</li></ul>";
	}
	function pagestring($count, $size) {
		$p = new Pager ( $count, $size, 'page' );
		return array ($p->offset, $size, $p->genBasic () );
	}
	
	//ajax 分页
	function noticeAjax($pagecount, $page, $result_num, $page_size) {
		$pagetable = "";
		$pagecountlist = "<table><tr><td width='565' align='center' class='small_page'>";
		
		if ($pagecount > 1) {
			$start = (ceil ( $page / 10 ) - 1) * 10;
			$end = ceil ( $page / 10 ) * 10 + 1;
			if ($start <= 0)
				$start = 1;
			if ($end >= $pagecount)
				$end = $pagecount;
			for($i = $start; $i <= $end; $i ++) {
				if ($page == $i)
					$pagecountlist .= '<a href="javascript:;" class="active" url="/company/notices?page=' . $i . '">' . $i . '</a>';
				else
					$pagecountlist .= '<a href="javascript:;"  url="/company/notices?page=' . $i . '">' . $i . '</a>';
			}
		}
		
		$pagetable .= $pagecountlist . "</td></tr></table>";
		
		return $pagetable;
	}
	
	//ajax 分页
	function noticeStudentAjax($pagecount, $page, $result_num, $page_size) {
		$pagetable = "";
		$pagecountlist = "<table><tr><td width='565' align='center' class='small_page'>";
		
		if ($pagecount > 1) {
			$start = (ceil ( $page / 10 ) - 1) * 10;
			$end = ceil ( $page / 10 ) * 10 + 1;
			if ($start <= 0)
				$start = 1;
			if ($end >= $pagecount)
				$end = $pagecount;
			for($i = $start; $i <= $end; $i ++) {
				if ($page == $i)
					$pagecountlist .= '<a href="javascript:;" class="active" url="/student/notices?page=' . $i . '">' . $i . '</a>';
				else
					$pagecountlist .= '<a href="javascript:;"  url="/student/notices?page=' . $i . '">' . $i . '</a>';
			}
		}
		
		$pagetable .= $pagecountlist . "</td></tr></table>";
		
		return $pagetable;
	}
}
?>
