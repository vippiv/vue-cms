<?php
	//在login.php页面已经保存了session
	//session是一个管理数组，其中保存了user_id和username
	//这里是要根据session_id拿到对应的session，如果能达到则说明处于登录状态
	//session放在服务器端，浏览器关闭就清除
	header("Access-Control-Allow-Origin:*");
	header("Content-type: text/html; charset=utf-8"); 
	include "lib/db.php";
	
	class CheckLogin{
		private $sessionStr;
		
		private $sessionObj;
		
		private $sn;
		
		private $errorInfo;
		
		private $_statusCode=[
			200=>"OK",
			204=>'No Content',
			400=>'Bad Request',
			401=>'Unauthorized',
			403=>'Forbidden',
			404=>'Not Found',
			405=>'Method Not Allow',
			500=>'Server Internal Error'
		];
		
		public function run(){
			$this->getSession();
			$this->check($this->sn);
		}
		
		private function getSession(){
			$this->sessionStr = file_get_contents('php://input');
			$this->sessionObj = json_decode($this->sessionStr);
//			$this->sn = $this->sessionObj["sessionId"];
			$this->sn = $this->sessionObj->sessionId; 
		}
		
		private function check($sn){
			if(empty($sn)){
				$this->errorInfo = [
					"errorCode" => 403,
					"errorMessage" => "请重新登录" 
				];
				$this->toJson($this->errorInfo,403);
				return;
			}
			session_id($sn);
			session_start();
			$this->toJson($_SESSION,200);
		}
		
		private function toJson($array,$code){
			//1.1后面少个空格就报错了
			header("HTTP/1.1 ".$code." ".$this->_statusCode[$code]);
			if($array!==null){
				echo json_encode($array,JSON_UNESCAPED_UNICODE);
			}
			exit();
		}
	}
	
	$check = new CheckLogin();
	$check->run();
	
	
//	var_dump($_SESSION);

?>