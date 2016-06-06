<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Template_Model extends CI_Model {
	const PPT = 'theme_file'; // 模版表
	const T = 'theme_info'; // 主题表
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取所有的主题
	 */
	function get_templates() {
		return $this->db->select ( '*' )->order_by ( 'state DESC' )->get_where ( self::T, 'id > 0' )->result_array ();
	}
	
	/**
	 * 修改主题状态
	 */
	function update_template($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::T, $data, $id );
		} else {
			$this->db->insert ( self::PPT, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 获取一条 主题
	 *
	 * @param number $id        	
	 */
	function get_template_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T, $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 统计申请条数
	 *
	 * @param string $where        	
	 */
	function count_ppt($where = null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::PPT )->count_all_results ();
	}
	
	/**
	 * 获取申请信息
	 *
	 * @param string $where
	 *        	条件
	 * @param number $limit
	 *        	偏移量
	 * @param number $offset        	
	 * @param string $orderby
	 *        	排序
	 * @author z.junjie 2014-6-28
	 */
	function get_ppt($where = null, $limit = 0, $offset = 0, $orderby = 'orderby desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
		
		return $this->db->order_by ( $orderby )->get ( self::PPT )->result ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			
			return $this->db->from ( self::PPT )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::PPT )->result ();
		}
		return array ();
	}
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::PPT, $data, $id );
		} else {
			$this->db->insert ( self::PPT, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::PPT, $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::PPT, 'id = ' . $id );
		}
	}
}