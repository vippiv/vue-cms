<?php
	//添加，删除管理员功能
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Methods:OPTIONS,GET,POST,OPTIONS,PUT,DELETE");
	header("Content-type: text/json; charset=utf-8"); 
	include "lib/db.php";
	include "lib/ErrorCode.php";
	
	class User{
		
		/*
		 *管理员用户名 
		 * */
		private $username;
		
		/*
		 *管理员密码
		 * */
		private $passward;
		
		/*
		 *管理员权限
		 * */
		private $ability;
		
		/*
		 *管理员对应id
		 * */
		private $id;
		
		/*
		 *错误信息
		 * */
		private $errorInfo;
		
		private $action;
		
		private $jsonstr;
		
		private $jsonObj;
		
		/*
		 *错误代码
		 * */
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
		
		public function _constructor(){
			
		}
		
		/*
		 *唯一的入口
		 * */
		public function run(){
			$this->action = $_GET["action"];
			try{
				if($this->action=="add"){
					$this->getUserInfo();
					$this->register($this->username,$this->passward,$this->ability);
				}else if($this->action=="del"){
					$this->id = $_GET["id"];
					$this->del($this->id);
				}else if($this->action=="list"){
					$this->userList();
				}else if($this->action=="update"){
					$this->getUserInfo();
					$this->id = $_GET["id"];
					$this->updateUser($this->id,$this->username,$this->passward,$this->ability);
				}else if($this->action=="view"){
					//获取单条信息
					$this->id = $_GET["id"];
					$this->view($this->id);
				}else{
					throw new Exception("缺少操作符",ErrorCode::LACK_OPERATOR);
				}
			}catch(Exception $e){
				$code = $e->getCode();
				if(!in_array($e->getCode(),$this->_statusCode)){
					$code = 400;
				}
				$this->toJson(['error'=>$e->getMessage()],$code);
			}
			
		}
		
		/*
		 *获得客户端传过来的数据
		 * */
		private function getUserInfo(){
			$this->jsonstr = file_get_contents('php://input');
			$this->jsonObj = json_decode($this->jsonstr);
		
			$this->username = $this->jsonObj->username;
			$this->passward = $this->jsonObj->passward;
			$this->ability = $this->jsonObj->ability;

//			$this->username = "admin2";
//			$this->passward = "123";
		}
		
		/**
		 * 用户注册
		 * @params $username
		 * @params $passward
		 * @return 新账户信息array
		*/
		private function register($username,$passward,$ability){
			if(empty($username)){
				throw new Exception("用户名不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			if(empty($passward)){
				throw new Exception("密码不能为空",ErrorCode::PASSWORD_CANNOT_EMPTY);
			}
			if($this->isUserExists($username)){
				throw new Exception("用户名已经存在",ErrorCode::USERNAME_EXISTS);
			}
			
			$createAt = date("y-m-d",time());
			
			//写入数据库
			$sql = 'insert into user(username,passward,createAt,ability) values("'.$username.'","'.$passward.'","'.$createAt.'","'.$ability.'")';
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $GLOBALS["mysqli"]->error);
//			    throw new Exception("注册失败",ErrorCode::REGISTER_FAIL);
			}	

			$successInfo = [
				'userId'=>mysqli_insert_id($GLOBALS["mysqli"]),
				'username' => $username,
				'createAt'=>$createAt
			];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 删除管理员信息
		 * @params $username
		 * @return bool
		*/
		private function del($id){
			if(empty($id)){
				throw new Exception("用户id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			
			//删除指定用户
			$sql = "delete from user where id='".$id."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $GLOBALS["mysqli"]->error);
//			    throw new Exception("注册失败",ErrorCode::REGISTER_FAIL);
			}
			$successInfo = [];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 更新管理员信息（更改密码之类）
		 * @params $id
		 * @return bool
		*/
		private function updateUser($id,$username,$passward,$ability){
			if(empty($id)){
				throw new Exception("用户id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			if(empty($passward)){
				throw new Exception("密码不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			
			//更新信息
			$sql = "update user set username='".$username."',passward='".$passward."',ability='".$ability."' where id='".$id."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $GLOBALS["mysqli"]->error);
			}
			$successInfo = [];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 获得单条信息
		 * @params $id
		 * @return array
		*/
		private function view($id){
			if(empty($id)){
				throw new Exception("id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			$sql = "select * from user where id=".$id;
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $mysqli->error);
			}
			$infoList = array();
			while ($rows = $res->fetch_assoc()){
			    $count=count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小  
			    for($i=0;$i<$count;$i++){  
			        unset($rows[$i]);//删除冗余数据  
			    }
			    array_push($infoList,$rows);
			}
			$this->toJson($infoList,200);
		}
		
		/**
		 * 获得管理员列表
		 * @params $username
		 * @return bool
		*/
		private function userList(){
			$sql = "select * from user";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $mysqli->error);
			}
			$userList = array();
			while ($rows = $res->fetch_assoc()){
			    $count=count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小  
			    for($i=0;$i<$count;$i++){  
			        unset($rows[$i]);//删除冗余数据  
			    }
			    array_push($userList,$rows);
			}
			$this->toJson($userList,200);
		}
		
		/**
		 * 检测用户名是否存在
		 * @params $username
		 * @return bool
		 * 
		*/
		private function isUserExists($username){
			$sql = "select * from user where username='".$username."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $mysqli->error);
			}
			$rows = $res->fetch_assoc();
			return !empty($rows);
		}
		
		private function toJson($array,$code){
			//1.1后面少个空格就报错了
			header("HTTP/1.1 ".$code." ".$this->_statusCode[$code]);
			if($array!==null){
				echo json_encode($array,JSON_UNESCAPED_UNICODE);
			}
			exit();
		}
		
		/**
		 * md5加密
		 * @params $string
		 * @params $string $key
		 * @return string
		*/
		private function _md5($string,$key='zwlcms'){
			return md5($string,$key);
		}
	}
	
	$u = new User();
	$u->run();
?>