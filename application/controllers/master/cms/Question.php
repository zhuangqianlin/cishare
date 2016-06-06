<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 常见问题管理
 *
 * @author junjiezhang
 *        
 */
class Question extends Master_Basic {
	/**
	 * PPT管理
	 *
	 * @var array
	 */
	protected $type;
	protected $bread;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$this->load->model ( $this->view . 'question_model' );
		$columnid = intval ( trim ( $this->input->get_post ( 'columnid' ) ) );
		// $this->bread = $this->_set_bread ( $columnid );
		// $this->load->vars ( 'bread_1', $this->bread ['bread_1'] );
		// $this->load->vars ( 'bread_2', $this->bread ['bread_2'] );
		// $this->load->vars ( 'bread_3', $this->bread ['bread_3'] );
	}
	
	/**
	 * 首页
	 * 管理ppt
	 * ppt 列表
	 */
	function index() {
		// 获取栏目的id
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
			
			$like = array ();
			$where = "id > 0 AND site_language = '{$_SESSION['language']}'";
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				OR
				orderby LIKE '%{$sSearch}%'
				OR
				state LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
		
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_0}%'
				OR
				title LIKE '%{$sSearch_0}%'
				OR
				orderby LIKE '%{$sSearch_0}%'
				OR
				state LIKE '%{$sSearch_0}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_0}%'
		
				)
				";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				OR
				title LIKE '%{$sSearch_1}%'
				OR
				orderby LIKE '%{$sSearch_1}%'
				OR
				state LIKE '%{$sSearch_1}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_1}%'
		
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_2}%'
				OR
				title LIKE '%{$sSearch_2}%'
				OR
				orderby LIKE '%{$sSearch_2}%'
				OR
				state LIKE '%{$sSearch_2}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_2}%'
		
				)
				";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				if ($sSearch_3 == - 1) {
					$sSearch_3 = 0;
				}
				$where .= "
				AND (
				
				state = {$sSearch_3}
				)
				";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_4}%'
				OR
				title LIKE '%{$sSearch_4}%'
				OR
				orderby LIKE '%{$sSearch_4}%'
				OR
				state LIKE '%{$sSearch_4}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
		
				)
				";
			}
			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->question_model->count_ppt ( $where );
			$output ['aaData'] = $this->question_model->get_ppt ( $where, $limit, $offset, 'orderby DESC' );
			
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$item->operation = '<a title="编辑" class="blue" href="/master/cms/question/edit?id=' . $item->id . '">
					<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
						<a title="删除" class="red" href="javascript:;" onclick="del( ' . $item->id . ')">
					<i class="ace-icon fa fa-trash-o bigger-130"></i>
					<a title="启用" class="red" href="javascript:;" onclick="edit_state(' . $item->id . ',1)">
					<i class="ace-icon fa fa-check green bigger-130"></i>
					</a>/<a title="停用" class="red" href="javascript:;" onclick="edit_state(' . $item->id . ',0)">
					<i class="ace-icon glyphicon glyphicon-remove red"></i>
					</a>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'question_index', array (
				'columnid' => $columnid 
		) );
	}
	
	/**
	 * 获取权限
	 */
	function _get_power() {
		$g = $this->db->select ( '*' )->get_where ( 'group_info', 'id = ' . $_SESSION ['master_user_info']->groupid )->row ();
		if ($g->is_read == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if (! empty ( $id )) {
			$info = $this->question_model->get_one ( $id );
			$this->_view ( 'question_add', array (
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function add() {
		$this->_view ( 'question_add' );
	}
	
	/**
	 * 状态
	 */
	function _set_state($state = 0) {
		$state_array = array (
				'停用',
				'启用' 
		);
		return $state_array [$state];
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'orderby',
				'state',
				'createtime' 
		);
	}
	
	/**
	 * 保存信息
	 */
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		
		if (! empty ( $data ['createtime'] )) {
			$data ['createtime'] = strtotime ( $data ['createtime'] );
		} else {
			$data ['createtime'] = time ();
		}
		$data ['site_language'] = $_SESSION ['language'];
		if (! empty ( $id )) {
			$flag = $this->question_model->save ( $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->question_model->save ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->question_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 修改状态
	 */
	function edit_state() {
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		if ($id) {
			$is = $this->question_model->save ( $id, array (
					'state' => $state 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}