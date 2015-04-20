<?php
// model 基本模型类
namespace app\extend;

use yii\data\Pagination;
use yii\widgets\LinkPager;

class ExtendActiveRecordModel extends \yii\db\ActiveRecord {
	/**
	 * 列表数据
	 */
	static public function baseFindList($class, $condition = []) {
		// 初始化ActiveQuery::find();
		$activeQuery = $class->find ();
		
		// 查询字段
		if (isset ( $condition ['SELECT'] ) && $condition ['SELECT']) {
			$activeQuery->select ( $condition ['SELECT'] );
		}
		// 关联表
		if (isset ( $condition ['WITH'] ) && $condition ['WITH']) {
			$activeQuery->joinWith ( $condition ['WITH'] );
		}
		// 排序条件
		if (isset ( $condition ['ORDER'] ) && $condition ['ORDER']) {
			$activeQuery->orderBy ( $condition ['ORDER'] );
		}
		// 查询条件
		$activeQuery = self::_batchCondition ( $activeQuery, $class->tableName (), $condition );
		
		$list = $activeQuery->asArray ()->all ();
		return $list;
	}
	
	/**
	 * 单条数据
	 */
	static public function baseFindRow($class, $condition) {
		// 初始化ActiveQuery::find();
		$activeQuery = $class->find ();
		
		// 查询字段
		if (isset ( $condition ['SELECT'] ) && $condition ['SELECT']) {
			$activeQuery->select ( $condition ['SELECT'] );
		}
		// 关联表
		if (isset ( $condition ['WITH'] ) && $condition ['WITH']) {
			$activeQuery->joinWith ( $condition ['WITH'] );
		}
		// 查询条件
		$activeQuery = self::_batchCondition ( $activeQuery, $class->tableName (), $condition );
		
		$row = $activeQuery->asArray ()->one ();
		return $row;
	}
	
	/**
	 * 翻页数据
	 */
	static public function baseFindPage($class, $condition = []) {
		// 初始化ActiveQuery::find();
		$activeQuery = $class->find ();
		
		// 查询字段
		if (isset ( $condition ['SELECT'] ) && $condition ['SELECT']) {
			$activeQuery->select ( $condition ['SELECT'] );
		}
		// 关联表
		if (isset ( $condition ['WITH'] ) && $condition ['WITH']) {
			$activeQuery->joinWith ( $condition ['WITH'] );
		}
		// 排序条件
		if (isset ( $condition ['ORDER'] ) && $condition ['ORDER']) {
			$activeQuery->orderBy ( $condition ['ORDER'] );
		}
		// 查询条件
		$activeQuery = self::_batchCondition ( $activeQuery, $class->tableName (), $condition );
		// 翻页数据
		$pageParams = isset ( $condition ['PAGEPARAMS'] ) ? $condition ['PAGEPARAMS'] : [ ];
		$pagination = self::_loadPagination ( $activeQuery, $condition ['PAGE'], $condition ['PAGESIZE'], $pageParams );
		
		$list = $activeQuery->offset ( $pagination->offset )->limit ( $pagination->limit )->asArray ()->all ();
		
		$return ['dataCount'] = $pagination->totalCount;
		$return ['pageCount'] = $pagination->pageCount;
		$return ['currentPage'] = $condition ['PAGE'];
		
		// 判断是否需要显示linkPager PC 需要
		if (isset ( $condition ['PAGELINK'] )) {
			$return ['linkPager'] = LinkPager::widget ( [ 
					'pagination' => $pagination,
					'nextPageLabel' => \Yii::$app->params ['nextPageLabel'],
					'prevPageLabel' => \Yii::$app->params ['prevPageLabel'] 
			] );
		}
		
		$return ['list'] = $list;
		
		return $return;
	}
	
	/**
	 * 批量查询条件解析赋值
	 *
	 * @param $activeQuery 查询对象        	
	 * @param $tableName 查询表名        	
	 * @param $condition 条件数组        	
	 * @return $activeQuery object
	 */
	static private function _batchCondition($activeQuery, $tableName, $condition) {


		$SQLoperator = ''; // SQL 操作符
		$SQLfield = ''; // SQL 字段
		
		foreach ( $condition as $key => $val ) {
			// 判断是否存在#
			if (strpos ( $key, '#' ) && $val !== null) {
				$arrKey = explode ( '#', $key ); // 解析条件
				switch ($arrKey [0]) {
					case 'EQ' :
						$SQLoperator = '=';
						break;
					case 'NEQ' :
						$SQLoperator = '!=';
						break;
					case 'GT' :
						$SQLoperator = '>';
						break;
					case 'LT' :
						$SQLoperator = '<';
						break;
					case 'GTE' :
						$SQLoperator = '>=';
						break;
					case 'LTE' :
						$SQLoperator = '<=';
						break;
					case 'LIKE' :
						$SQLoperator = 'LIKE';
						break;
					case 'NLIKE' :
						$SQLoperator = 'NOT LIKE';
						break;
					case 'IN' :
						$SQLoperator = 'IN';
						break;
					case 'NIN' :
						$SQLoperator = 'NOT IN';
						break;
				}
				
				// 判断是否存在.
				if (strpos ( $arrKey [1], '.' )) {
					$SQLfield = $arrKey [1];
				} else {
					$SQLfield = "{$tableName}.{$arrKey[1]}";
				}
				
				$activeQuery->andWhere ( [ 
						$SQLoperator,
						$SQLfield,
						$val 
				] );
			}
		}
		
		return $activeQuery;
	}
	
	/**
	 * 读取翻页数据
	 *
	 * @param $activeQuery 查询对象        	
	 * @param $currentPage 当前页数        	
	 * @param $pageSize 每页条数        	
	 * @param $urlParams get参数        	
	 * @return $pages object
	 */
	static private function _loadPagination($activeQuery, $currentPage, $pageSize, $urlParams) {
		$pagination = new Pagination ( [ 
				'page' => intval ( $currentPage ) - 1,
				'pageSize' => $pageSize,
				'totalCount' => $activeQuery->count (),
				'params' => $urlParams 
		] );
		
		return $pagination;
	}
} 
