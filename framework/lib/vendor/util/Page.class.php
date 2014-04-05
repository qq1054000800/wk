<?php
class Page {
	// 分页URL地址
	public $url = '';
	// 起始行数
	public $firstRow;
	// 当前页数
	protected $nowPage;
	// 分页的栏的总页数
	protected $coolPages;
	// 默认分页变量名
	protected $varPage;
	// 总行数
	protected $totalRows;
	// 默认列表每页显示行数
	public $listRows = 20;
	// 分页总页面数
	protected $totalPages;
	// 分页栏每页显示的页数
	public $rollPage = 5;

	/**
	 * 架构函数
	 * @access public
	 * @param array $totalRows
	 *            总的记录数
	 * @param array $listRows
	 *            每页显示记录数
	 * @param array $parameter
	 *            分页跳转的参数
	 */
	public function __construct($totalRows, $listRows = '', $url = '') {
		$this->totalRows = $totalRows; //总行数
		$this->varPage   = 'p'; //分页参数
		if (!empty($listRows)) {
			$this->listRows = intval($listRows);
		}
		$this->totalPages = ceil($this->totalRows / $this->listRows); // 总页数
		$this->coolPages  = ceil($this->totalPages / $this->rollPage);
		$this->nowPage    = !empty($_GET[$this->varPage]) ? intval($_GET[$this->varPage]) : 1; //当前页数
		if ($this->nowPage < 1) {
			$this->nowPage = 1;
		} elseif (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
			$this->nowPage = $this->totalPages;
		}
		$this->firstRow = $this->listRows * ($this->nowPage - 1); //第一行记录的行
		if (!empty($url)) {
			$this->url = $url;
		}
	}

	/**
	 * 分页信息
	 * totalRows// 总条数
	 * listRows// 每页显示条数
	 * totalPage// 总页数
	 * nowPage// 当前页
	 * theFirstUrl// 第一页 <<
	 * prePageUrl// 上5页,大翻页 <
	 * nextPageUrl// 下5也,大翻页 >
	 * theEndUrl// 最后一页 >>
	 * linkPageUrl//数字衔接 1 2 3 4 5
	 */
	public function show() {
		$pageInfo = array(); // 分页衔接信息
		if ($this->totalRows == 0) {
			return array(
				'start'  => 0,
				'length' => 0
			);
		}
		$pageInfo['start']     = $this->firstRow; //SQL中开始条数
		$pageInfo['length']    = $this->listRows; //SQL中长度
		$pageInfo['totalRows'] = $this->totalRows; // 总条数
		$pageInfo['nowPage']   = $this->nowPage; // 当前页
		$pageInfo['totalPage'] = $this->totalPages; // 总页数
		$pageInfo['listRows']  = $this->listRows; // 每页显示条数
		$nowCoolPage           = ceil($this->nowPage / $this->rollPage);
		// 上下翻页字符串
		$upRow                   = $this->nowPage - 1;
		$downRow                 = $this->nowPage + 1;
		$pageInfo['upPageUrl']   = $this->getUrl($upRow); // 上一页衔接
		$pageInfo['downPageUrl'] = $this->getUrl($downRow); // 下一页衔接
		// << < > >>
		if ($nowCoolPage == 1) {
			$pageInfo['theFirstUrl'] = ''; // 第一页
			$pageInfo['prePageUrl']  = ''; // 上5页,大翻页
		} else {
			$pageInfo['theFirstUrl'] = $this->getUrl(1);
			$pageInfo['prePage']     = $this->getUrl($this->nowPage - $this->rollPage);
		}
		if ($nowCoolPage == $this->coolPages) {
			$pageInfo['nextPageUrl'] = ''; // 下5也,大翻页
			$pageInfo['theEndUrl']   = ''; // 最后一页
		} else {
			$pageInfo['nextPageUrl'] = $this->getUrl($this->nowPage + $this->rollPage);
			$pageInfo['theEndUrl']   = $this->getUrl($this->totalPages);
		}
		// 1 2 3 4 5数字衔接
		$pageInfo['linkPageUrl'] = array(); // 数字衔接
		for ($i = 1; $i <= $this->rollPage; $i++) {
			$page = ($nowCoolPage - 1) * $this->rollPage + $i;
			if ($page <= $this->totalPages) {
				$pageInfo['linkPageUrl'][$page] = $this->getUrl($page);
			} else {
				break;
			}
		}
		return $pageInfo;
	}

	private function  getUrl($pageNum) {
		$pageUrl = $this->url.'?'.$this->varPage.'='.$pageNum;
		return $pageUrl;
	}
}
