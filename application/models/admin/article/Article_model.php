<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 文章管理
 *
 * @author zhuangqianlin
 *        
 */
class Article_Model extends CI_Model {
	const TABLE_ARTICLE = 'article';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
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
		return $this->db->from ( self::TABLE_ARTICLE )->count_all_results ();
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
	function getList($where = null, $limit = 0, $offset = 0, $orderby = 'article_id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
		
		return $this->db->order_by ( $orderby )->get ( self::TABLE_ARTICLE )->result ();
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
			
			return $this->db->from ( self::TABLE_ARTICLE )->count_all_results ();
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
			return $this->db->get ( self::TABLE_ARTICLE )->result ();
		}
		return array ();
	}
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::TABLE_ARTICLE, $data, 'article_id = ' . $id );
		} else {
			$this->db->insert ( self::TABLE_ARTICLE, $data );
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
			return $this->db->get_where ( self::TABLE_ARTICLE, 'article_id = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::TABLE_ARTICLE, 'article_id = ' . $id );
		}
	}
}