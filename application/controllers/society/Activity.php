<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 学生 控制器
 *
 * @author zyj
 *        
 */
class Activity extends society_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_societylogin ();
		
		$this->load->model ( 'society/activity_model' );
	}
	
	/**
	 * 我报名的活动
	 */
	function launch() {
		$this->load->library ( 'pager' ); // 调用分页类
		$where = 'userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND type =2';
		$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$getcount = $this->activity_model->counts ( $where, 10 );
		$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
		
		list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 10 );
		
		$result = $this->activity_model->getall ( $where, '*', $offset, $size, '(`starttime`- UNIX_TIMESTAMP()) > 0 DESC,(abs(`starttime`- UNIX_TIMESTAMP())) ASC,id DESC' );
		
		// 计算报名人数
		if (! empty ( $result )) {
			foreach ( $result as $k => $v ) {
				$count [$v ['id']] = $this->activity_model->countuser ( 'activityid = ' . $v ['id'] );
			}
		}
		
		$this->load->view ( 'society/activity_launch', array (
				'result' => ! empty ( $result ) ? $result : array (),
				'pagecount' => $pagecount,
				'page' => $page,
				'count' => ! empty ( $count ) ? $count : array () 
		) );
	}
	
	/**
	 * 加载更多
	 * 我发起的活动
	 */
	function get_activity_launch() {
		$page = trim ( $this->input->get ( 'page' ) );
		
		if ($page) {
			
			$this->load->library ( 'pager' ); // 璋冪敤鍒嗛〉绫�
			$where = 'userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND type = 2';
			
			$getcount = $this->activity_model->counts ( $where, 10 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			if ($page <= $pagecount) {
				$size = 10;
				$offset = ($page - 1) * $size;
				$result = $this->activity_model->getall ( $where, '*', $offset, $size, '(`starttime`- UNIX_TIMESTAMP()) > 0 DESC,(abs(`starttime`- UNIX_TIMESTAMP())) ASC,id DESC' );
				
				// 鑾峰彇绫诲瀷 鍥藉埆 姣嶇殑
				$html = $this->_view ( 'society/get_activity_launch', array (
						'result' => ! empty ( $result ) ? $result : array (),
						'pagecount' => $pagecount,
						'page' => $page 
				), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'no_data' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'no_data' ), 0 );
		}
	}
	
	/**
	 * 发起活动
	 */
	function add() {
		$this->load->view ( 'society/activity_add', array () );
	}
	
	/**
	 * 添加内容
	 */
	function add_c() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			// 权限判断
			$flag = $this->_authority ( $id );
			if ($flag) {
				$result = $this->activity_model->get_one_base ( 'id = ' . $id . ' AND type = 2' );
				$content = $this->activity_model->get_one_content ( 'activityid = ' . $id . " AND site_language =  '{$this->puri}'" );
				$this->load->view ( 'society/activity_add_content', array (
						'result' => ! empty ( $result ) ? $result : array (),
						'content' => ! empty ( $content ) ? $content : array () 
				) );
			} else {
				$this->load->view ( 'society/activity_authority', array (
						'info' => lang ( 'activity_authority' ) 
				) );
			}
		}
	}
	
	/**
	 * 权限判断
	 */
	function _authority($id = null) {
		$flag = false;
		if ($id != null) {
			// 获取一条数据
			$result = $this->activity_model->get_one_base ( 'id = ' . $id . ' AND type = 2' );
			if (! empty ( $result ) && $result ['userid'] == $_SESSION ['society'] ['userinfo'] ['id']) {
				$flag = true;
			}
		}
		return $flag;
	}
	
	/**
	 * 保存活动
	 */
	function save() {
		$data = $this->input->post ();
		foreach ( $data as $k => $v ) {
			$data [$k] = mysql_real_escape_string ( $v );
		}
		if (! empty ( $data ['starttime'] )) {
			$data ['starttime'] = strtotime ( $data ['starttime'] );
		}
		if (! empty ( $data ['endtime'] )) {
			$data ['endtime'] = strtotime ( $data ['endtime'] );
		}
		
		if (! empty ( $data )) {
			$data ['userid'] = $_SESSION ['society'] ['userinfo'] ['id'];
			$data ['username'] = ! empty ( $_SESSION ['society'] ['userinfo'] ['enname'] ) ? $_SESSION ['society'] ['userinfo'] ['enname'] : '';
			$data ['createtime'] = time ();
			$data ['type'] = 2;
			$flag = $this->activity_model->save_base ( $data );
			if ($flag) {
				ajaxReturn ( '', lang ( 'update_success' ), 1 );
			} else {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}
	
	/**
	 * 保存 内容
	 */
	function savec() {
		$data = $this->input->post ();
		foreach ( $data as $k => $v ) {
			$data [$k] = mysql_real_escape_string ( $v );
		}
		
		$id = intval ( trim ( $data ['id'] ) );
		if (! empty ( $id )) {
			// 权限判断
			$flag = $this->_authority ( $id );
			if ($flag) {
				// 基本信息 内容信息
				$dataBase = $dataContent = array ();
				$flag1 = $flag2 = false;
				if (! empty ( $data ['starttime'] )) {
					$dataBase ['starttime'] = strtotime ( $data ['starttime'] );
				}
				if (! empty ( $data ['endtime'] )) {
					$dataBase ['endtime'] = strtotime ( $data ['endtime'] );
				}
				
				if (! empty ( $data ['ctitle'] )) {
					$dataBase ['ctitle'] = $data ['ctitle'];
				}
				if (! empty ( $data ['etitle'] )) {
					$dataBase ['etitle'] = $data ['etitle'];
				}
				
				$dataBase ['isapply'] = $data ['isapply'];
				// 内容
				if (! empty ( $data ['linkname'] )) {
					$dataContent ['linkname'] = $data ['linkname'];
				}
				if (! empty ( $data ['linktel'] )) {
					$dataContent ['linktel'] = $data ['linktel'];
				}
				if (! empty ( $data ['budgeting'] )) {
					$dataContent ['budgeting'] = $data ['budgeting'];
				}
				if (! empty ( $data ['address'] )) {
					$dataContent ['address'] = $data ['address'];
				}
				if (! empty ( $data ['info'] )) {
					$dataContent ['info'] = $data ['info'];
				}
				if (! empty ( $data ['content'] )) {
					$dataContent ['content'] = $data ['content'];
				}
				if (! empty ( $data ['image'] )) {
					$dataContent ['image'] = $data ['image'];
				}
				if (! empty ( $data ['info'] )) {
					$dataContent ['info'] = $data ['info'];
				}
				
				if (! empty ( $dataBase )) {
					$dataBase ['updatetime'] = time ();
					$flag1 = $this->activity_model->update_base ( 'id = ' . $id, $dataBase );
					if (! empty ( $dataContent )) {
						$result = $this->activity_model->get_one_content ( 'activityid = ' . $id . " AND site_language =  '{$this->puri}'" );
						if (! empty ( $result )) {
							$this->activity_model->del_content ( 'activityid = ' . $id . " AND site_language =  '{$this->puri}'" );
						}
						
						if (! empty ( $dataContent )) {
							$dataContent ['activityid'] = $id;
							$dataContent ['site_language'] = $this->puri;
							$flag2 = $this->activity_model->save_content ( $dataContent );
						}
					}
					
					if ($flag2) {
						ajaxReturn ( '', lang ( 'update_success' ), 0 );
					} else {
						ajaxReturn ( '', lang ( 'update_error' ), 0 );
					}
				}
			} else {
				ajaxReturn ( '', lang ( 'update_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'update_error' ), 0 );
		}
	}
	
	/**
	 * 获取在学信息
	 */
	function _get_society($userid = null) {
		$data = array ();
		if ($userid != null) {
			$rel = $this->db->select ( 'majorid,squadid' )->get_where ( 'society', 'userid = ' . $userid )->row ();
			if (! empty ( $rel->majorid )) {
				$rel_m = $this->db->select ( 'englishname,id' )->get_where ( 'major', 'id = ' . $rel->majorid )->row ();
				if (! empty ( $rel_m->englishname )) {
					$data ['majorid'] = $rel_m->englishname;
				}
			}
			
			if (! empty ( $rel->squadid )) {
				$rel_s = $this->db->select ( 'englishname,id' )->get_where ( 'squad', 'id = ' . $rel->squadid )->row ();
				if (! empty ( $rel_s->englishname )) {
					$data ['squadid'] = $rel_s->englishname;
				}
			}
		}
		return $data;
	}
	
	/**
	 * 判断是否有权限 取消活动
	 */
	function _activity_cancel_join_authority($id = null) {
		$return = array (
				'flag' => 0,
				'info' => lang ( 'activity_cancel_noauthority' ) 
		);
		if ($id != null) {
			// 判断时候已经加
			$user_a = $this->activity_model->get_activity_user_mine ( 'activityid = ' . $id . ' AND userid  = ' . $_SESSION ['society'] ['userinfo'] ['id'] );
			if (! empty ( $user_a )) {
				if ($user_a ['state'] == 0) {
					return array (
							'flag' => 1 
					);
				} else {
					return array (
							'flag' => 0,
							'info' => lang ( 'activity_cancel_noauthority' ) 
					);
				}
			} else {
				return array (
						'flag' => 0,
						'info' => lang ( 'activity_cancel_noauthority' ) 
				);
			}
		}
		
		return $return;
	}
	
	/**
	 * 查看报名人
	 */
	function ckuser() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			// 判断开关
			$on_off = CF ( 'function_on_off', '', CONFIG_PATH );
			$on = 0;
			if (! empty ( $on_off ['activity'] ) && $on_off ['activity'] == 'yes') {
				$on = 1;
			}
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			$this->load->library ( 'pager' ); // 调用分页类
			$where = 'activityid = ' . $id;
			$page = ! empty ( $_GET ['page'] ) ? $_GET ['page'] : 1;
			$getcount = $this->activity_model->countsuser ( $where, 10 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			
			list ( $offset, $size, $pagestring ) = $this->pager->pagestring ( $getcount ['allcount'], 10 );
			
			$result = $this->activity_model->getalluser ( $where, '*', $offset, $size, 'id DESC,applytime DESC' );
			// if (! empty ( $result )) {
			// // 组合数组
			// foreach ( $result as $k => $v ) {
			// // 我参与的活动的id
			// $aid [] = $v ['activityid'];
			// }
			// $whereA = 'state = 1 AND id IN (' . implode ( ',', $aid ) . ')';
			// $activityAll = $this->activity_model->get_all_act ( $whereA );
			// if (! empty ( $activityAll )) {
			// foreach ( $activityAll as $key => $val ) {
			// $myact [$val ['id']] = $val;
			// }
			// }
			// }
			
			$this->load->view ( 'society/activity_ckuser', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'pagecount' => $pagecount,
					'page' => $page,
					'nationality' => $nationality,
					'on' => $on 
			// 'myact' => ! empty ( $myact ) ? $myact : array ()
						) );
		}
	}
	
	/**
	 * 加载更多
	 * 我发起的活动
	 */
	function get_activity_ckuser() {
		$page = trim ( $this->input->get ( 'page' ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		if ($page && $id) {
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			// 判断开关
			$on_off = CF ( 'function_on_off', '', CONFIG_PATH );
			$on = 0;
			if (! empty ( $on_off ['activity'] ) && $on_off ['activity'] == 'yes') {
				$on = 1;
			}
			$this->load->library ( 'pager' ); // 璋冪敤鍒嗛〉绫�
			$where = 'activityid = ' . $id;
			
			$getcount = $this->activity_model->countsuser ( $where, 10 );
			$pagecount = ! empty ( $getcount ['pagecount'] ) ? $getcount ['pagecount'] : 1;
			if ($page <= $pagecount) {
				$size = 10;
				$offset = ($page - 1) * $size;
				$result = $this->activity_model->getalluser ( $where, '*', $offset, $size, 'id DESC,applytime DESC' );
				// if (! empty ( $result )) {
				// // 组合数组
				// foreach ( $result as $k => $v ) {
				// // 我参与的活动的id
				// $aid [] = $v ['activityid'];
				// }
				// $whereA = 'state = 1 AND id IN (' . implode ( ',', $aid ) . ')';
				// $activityAll = $this->activity_model->get_all_act ( $whereA );
				// if (! empty ( $activityAll )) {
				// foreach ( $activityAll as $key => $val ) {
				// $myact [$val ['id']] = $val;
				// }
				// }
				// }
				$html = $this->_view ( 'society/get_activity_ckuser', array (
						'result' => ! empty ( $result ) ? $result : array (),
						'pagecount' => $pagecount,
						'page' => $page,
						'nationality' => $nationality,
						'on' => $on 
				// 'myact' => ! empty ( $myact ) ? $myact : array ()
								), true );
				ajaxReturn ( $html, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'no_data' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'no_data' ), 0 );
		}
	}
	
	/**
	 * 设置申请人是否参加活动
	 */
	function activity_join_set() {
		$activityid = intval ( trim ( $this->input->post ( 'activityid' ) ) );
		$userid = intval ( trim ( $this->input->post ( 'userid' ) ) );
		$isjoin = intval ( trim ( $this->input->post ( 'isjoin' ) ) );
		if ($activityid && $userid && $isjoin) {
			// 判断是有权限去设置
			$flag = $this->_activity_join_set_authority ( $activityid, $userid );
			if (! empty ( $flag )) {
				if ($flag ['flag'] == 1) {
					if ($isjoin == - 1) {
						$isjoin = 0;
					}
					$f = $this->activity_model->update_activity_user ( 'activityid = ' . $activityid . ' AND userid = ' . $userid, array (
							'isjoin' => $isjoin 
					) );
					if ($isjoin == 0) {
						// 在学表中加一
						$countA = $this->db->select ( 'joincounts' )->get_where ( 'society', 'userid = ' . $userid )->row ();
						$c = ! empty ( $countA->joincounts ) ? $countA->joincounts : 0;
						$c ++;
						$this->db->update ( 'society', array (
								'joincounts' => $c 
						), 'userid = ' . $userid );
					}
					
					if ($f) {
						ajaxReturn ( '', lang ( 'activity_isapply_set_success' ), 1 );
					} else {
						ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
					}
				} else {
					ajaxReturn ( '', $flag ['info'], 0 );
				}
			} else {
				ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
		}
	}
	
	/**
	 * 审核报名人
	 */
	function activity_isapply_check() {
		$activityid = intval ( trim ( $this->input->post ( 'activityid' ) ) );
		$userid = intval ( trim ( $this->input->post ( 'userid' ) ) );
		$state = intval ( trim ( $this->input->post ( 'state' ) ) );
		$auditopinion = trim ( $this->input->post ( 'auditopinion' ) );
		if ($activityid && $userid && $state) {
			// 判断是有权限去设置
			$flag = $this->_activity_join_set_authority ( $activityid, $userid );
			if (! empty ( $flag )) {
				if ($flag ['flag'] == 1) {
					
					$f = $this->activity_model->update_activity_user ( 'activityid = ' . $activityid . ' AND userid = ' . $userid, array (
							'state' => $state,
							'auditopinion' => ! empty ( $auditopinion ) ? mysql_real_escape_string ( $auditopinion ) : '',
							'auditid' => $_SESSION ['society'] ['userinfo'] ['id'],
							'auditname' => ! empty ( $_SESSION ['society'] ['userinfo'] ['enname'] ) ? $_SESSION ['society'] ['userinfo'] ['enname'] : '',
							'audittime' => time () 
					) );
					if ($f) {
						ajaxReturn ( '', lang ( 'activity_isapply_set_success' ), 1 );
					} else {
						ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
					}
				} else {
					ajaxReturn ( '', $flag ['info'], 0 );
				}
			} else {
				ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
		}
	}
	
	/**
	 * 打分
	 */
	function activity_isapply_score() {
		$activityid = intval ( trim ( $this->input->post ( 'activityid' ) ) );
		$userid = intval ( trim ( $this->input->post ( 'userid' ) ) );
		$score = intval ( trim ( $this->input->post ( 'score' ) ) );
		if ($activityid && $userid && $score) {
			// 判断是有权限去设置
			$flag = $this->_activity_join_set_authority ( $activityid, $userid );
			if (! empty ( $flag )) {
				if ($flag ['flag'] == 1) {
					
					$f = $this->activity_model->update_activity_user ( 'activityid = ' . $activityid . ' AND userid = ' . $userid, array (
							'score' => $score 
					) );
					if ($f) {
						
						// 判断是否都已经打了分
						$df = $this->db->select ( 'score' )->get_where ( 'activity_user', 'state = 1 AND activityid = ' . $activityid )->result_array ();
						if (! empty ( $df )) {
							foreach ( $df as $key => $val ) {
								if (empty ( $val ['score'] )) {
									$b = 0;
									break;
								} else {
									$b = 1;
								}
							}
							if ($b == 1) {
								// 更新活动为已打分
								$this->activity_model->update_base ( 'id = ' . $activityid . ' AND type = 2', array (
										'isscoreend' => 1 
								) );
							}
						}
						
						ajaxReturn ( '', lang ( 'activity_isapply_set_success' ), 1 );
					} else {
						ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
					}
				} else {
					ajaxReturn ( '', $flag ['info'], 0 );
				}
			} else {
				ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'activity_isapply_set_error' ), 0 );
		}
	}
	
	/**
	 * 判断是否有权限设置
	 */
	function _activity_join_set_authority($activityid = null, $useid = null) {
		$return = array (
				'flag' => 0,
				'info' => lang ( 'activity_isapply_noauthority' ) 
		);
		if ($activityid != null && $useid != null) {
			// 首先判断此活动 是本人发的并且是 通过的
			$activityinfo = $this->activity_model->get_one_base ( 'state = 1 AND id = ' . $activityid . ' AND userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND type = 2' );
			if (empty ( $activityinfo )) {
				return array (
						'flag' => 0,
						'info' => lang ( 'activity_isapply_set_error' ) 
				);
			}
			
			// 判断这这个人是否参
			$user = $this->activity_model->get_activity_user_mine ( 'userid = ' . $useid . ' AND activityid = ' . $activityid );
			if (! empty ( $user )) {
				return array (
						'flag' => 1 
				);
			} else {
				return array (
						'flag' => 0,
						'info' => lang ( 'activity_isapply_set_error' ) 
				);
			}
		}
		
		return $return;
	}
	
	/**
	 * 撤销活动
	 */
	function js_activity() {
		$id = intval ( trim ( $this->input->post ( 'id' ) ) );
		if ($id) {
			// 判断权限
			$flag = $this->_ishandend_activity_authority ( $id );
			if (! empty ( $flag ) && $flag ['flag'] == 1) {
				$f = $this->activity_model->update_base ( 'id = ' . $id, array (
						'ishandend' => 0 
				) );
				if ($f) {
					ajaxReturn ( '', lang ( 'ishandend_success' ), 1 );
				} else {
					ajaxReturn ( '', lang ( 'ishandend_error' ), 0 );
				}
			} else {
				ajaxReturn ( '', lang ( 'ishandend_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'ishandend_error' ), 0 );
		}
	}
	
	/**
	 * 判断撤销权限
	 */
	function _ishandend_activity_authority($id = null) {
		$return = array (
				'flag' => 0,
				'info' => lang ( 'ishandend_error' ) 
		);
		if ($id != null) {
			$flag = $this->activity_model->get_one_base ( 'userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND id = ' . $id . ' AND type = 2' );
			if (! empty ( $flag )) {
				return array (
						'flag' => 1 
				);
			} else {
				return array (
						'flag' => 0,
						'info' => lang ( 'ishandend_error' ) 
				);
			}
		}
		
		return $return;
	}
	
	/**
	 * 撤销活动
	 */
	function del_activity() {
		$id = intval ( trim ( $this->input->post ( 'id' ) ) );
		if ($id) {
			// 判断权限
			$flag = $this->_del_activity_authority ( $id );
			if (! empty ( $flag ) && $flag ['flag'] == 1) {
				$f = $this->activity_model->del_activity_base_content ( $id );
				if ($f) {
					ajaxReturn ( '', lang ( 'activity_del_success' ), 1 );
				} else {
					ajaxReturn ( '', lang ( 'activity_del_error' ), 0 );
				}
			} else {
				ajaxReturn ( '', lang ( 'activity_del_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'activity_del_error' ), 0 );
		}
	}
	
	/**
	 * 判断撤销权限
	 */
	function _del_activity_authority($id = null) {
		$return = array (
				'flag' => 0,
				'info' => lang ( 'activity_del_error' ) 
		);
		if ($id != null) {
			$flag = $this->activity_model->get_one_base ( 'state = 0 AND userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND id = ' . $id . ' AND type = 2' );
			if (! empty ( $flag )) {
				return array (
						'flag' => 1 
				);
			} else {
				return array (
						'flag' => 0,
						'info' => lang ( 'activity_del_error' ) 
				);
			}
		}
		
		return $return;
	}
	
	/**
	 * 查看相同报名人
	 */
	function ckusersame() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			// 首先判断 自己是否参加了这个活动 并且 已经通过了
			$flag = $this->activity_model->get_activity_user_mine ( 'userid = ' . $_SESSION ['society'] ['userinfo'] ['id'] . ' AND activityid = ' . $id );
			if ($flag) {
				$result = $this->db->select ( '*' )->get_where ( 'activity_user', 'state = 1 AND activityid = ' . $id . ' AND userid != ' . $_SESSION ['society'] ['userinfo'] ['id'] )->result_array ();
			}
			$this->load->view ( 'society/activity_ckusersame', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'nationality' => $nationality 
			) );
		}
	}
	function detail() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$result = $this->activity_model->get_one_base ( 'id = ' . $id . ' AND type = 2' );
			if (! empty ( $result )) {
				$content = $this->activity_model->get_one_content ( 'activityid = ' . $id . " AND site_language = '{$this->puri}'" );
			}
			
			$this->load->view ( 'society/activity_detail', array (
					'result' => ! empty ( $result ) ? $result : array (),
					'content' => ! empty ( $content ) ? $content : array () 
			) );
		}
	}
}
