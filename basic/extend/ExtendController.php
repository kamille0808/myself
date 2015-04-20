<?php
// 自定义扩展controller
namespace app\extend;

use yii\helpers\HtmlPurifier;

class ExtendController extends \yii\web\Controller {
	/**
	 * 获取POST数据
	 */
	static public function getPost($name = null, $defaultValue = null) {
		if ($name === null) {
			return self::_filterRequest ( $_POST );
		} else {
			return isset ( $_POST [$name] ) ? self::_filterRequest ( $_POST [$name] ) : $defaultValue;
		}
	}
	
	/**
	 * 获取GET数据
	 */
	static public function getQuery($name = null, $defaultValue = null) {
		if ($name === null) {
			return self::_filterRequest ( $_GET );
		} else {
			return isset ( $_GET [$name] ) ? self::_filterRequest ( $_GET [$name] ) : $defaultValue;
		}
	}
	
	/**
	 * 获取POST & GET 数据
	 */
	static public function getRequest($name = null, $defaultValue = null) {
		if ($name === null) {
			return self::_filterRequest ( $_REQUEST );
		} else {
			return isset ( $_REQUEST [$name] ) ? self::_filterRequest ( $_REQUEST [$name] ) : $defaultValue;
		}
	}
	
	/**
	 * 过滤请求数据 html <> script '' "" 等
	 *
	 * @param $data 查询数据        	
	 * @return 过滤后的数据
	 */
	static private function _filterRequest($data) {
		if ($data) {
			if (is_array ( $data )) {
				foreach ( $data as $key => $val ) {
					if(is_array ( $val )){
						$data [$key] = self::_filterRequest($val);
					}else{
						$data [$key] = HtmlPurifier::process ( $val );
					}
				}
				return $data;
			} else {
				return HtmlPurifier::process ( $data );
			}
		} else {
			return '';
		}
	}
	
	/**
	 * Api返回JSON
	 * status 0成功 1失败
	 * data 返回的数据
	 * msg 返回的操作信息
	 */
	static public function apiReturn($status, $data = array(), $msg = '操作成功') {
		header ( 'Content-type: text/json; charset=utf-8' );
	
		$apiReturn ['status'] = $status;
		$apiReturn ['data'] = self::_foreachEmpty ( $data );
		$apiReturn ['msg'] = $msg;
	
		// 记录日志
		// Yii::log ( CJSON::encode ( $_POST ), 'info', 'apiRequest-' . $this->getAction ()->getId () );
		// Yii::log ( CJSON::encode ( $return ), 'info', 'apiResponse-' . $this->getAction ()->getId () );
	
		die ( json_encode ( $apiReturn ) );
	}
	
	/**
	 * 遍历多维数组 把null值 设为空
	 */
	static private function _foreachEmpty($data) {
		if (is_string ( $data ) && ! $data) {
			return '';
		} elseif (is_array ( $data ) && ! $data) {
			return array ();
		} elseif (is_object ( $data )) {
			$is_empty = true;
			foreach ( $data as $val ) {
				$is_empty = false;
			}
			if ($is_empty) {
				return new stdClass ();
			}
		}
	
		foreach ( $data as $key => $val ) {
			if (is_array ( $val ) || is_object ( $val )) {
				$return [$key] = self::_foreachEmpty ( $val );
			} else {
				$return [$key] = $val === NULL ? '' : strval ( $val );
			}
		}
	
		return $return;
	}
} 
