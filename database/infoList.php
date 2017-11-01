<?php
	//添加，删除管理员功能
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Methods:OPTIONS,GET,POST,OPTIONS,PUT,DELETE");
	header("Content-type: text/json; charset=utf-8"); 
	include "lib/db.php";
	include "lib/ErrorCode.php";
	
	class InfoList{
		
		/*
		 *公司名
		 * */
		private $companyName;
		
		/*
		 *公司网址
		 * */
		private $website;
		
		/*
		 *手机号码
		 * */
		private $mobilephone;
		
		/*
		 *公司座机
		 * */
		private $telephone;
		
		/*
		 *邮箱
		 * */
		private $email;
		
		/*
		 *QQ
		 * */
		private $qq;
		
		/*
		 *备注
		 * */
		private $note;
		
		
		/*
		 *信息id
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
					//添加信息
					$this->getCompanyInfo();
					$this->addInfo($this->companyName,$this->website,$this->mobilephone,$this->telephone,$this->email,$this->qq,$this->note);
				}else if($this->action=="del"){
					//删除信息
					$this->id = $_GET["id"];
					$this->del($this->id);
				}else if($this->action=="list"){
					$page = $_GET["page"];
					$size = $_GET["size"];
					//获取信息列表
					$this->getList($page,$size);
				}else if($this->action=="view"){
					//获取单条信息
					$this->id = $_GET["id"];
					$this->view($this->id);
				}else if($this->action=="update"){
					//获取单条信息
					$this->id = $_GET["id"];
					//获得要更新的所有信息
					$this->getCompanyInfo();
					$this->update($this->id,$this->companyName,$this->website,$this->mobilephone,$this->telephone,$this->email,$this->qq,$this->note);
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
		private function getCompanyInfo(){
			$this->jsonstr = file_get_contents('php://input');
			$this->jsonObj = json_decode($this->jsonstr);
		
			$this->companyName = $this->jsonObj->companyName;
			$this->website = $this->jsonObj->website;
			$this->mobilephone = $this->jsonObj->mobilephone;
			$this->telephone = $this->jsonObj->telephone;
			$this->email = $this->jsonObj->email;
			$this->qq = $this->jsonObj->qq;
			$this->note = $this->jsonObj->note;

//			$this->username = "admin2";
//			$this->passward = "123";
		}
		
		/**
		 * 添加公司信息
		 * @params $companyName
		 * @params $website
		 * @params $mobilephone
		 * @params $telephone
		 * @params $email
		 * @params $qq
		 * @params $note
		 * @return 新公司信息array
		*/
		private function addInfo($companyName="",$website="",$mobilephone="",$telephone="",$email="",$qq="",$note=""){
			if(empty($companyName)){
				throw new Exception("公司名称不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			if($this->isCompanyExists($companyName)){
				throw new Exception("该公司已经存在",ErrorCode::USERNAME_EXISTS);
			}
			
			$createAt = time();
			
			//写入数据库
			$sql = 'insert into customer(cname,website,mobilephone,telephone,email,qq,note) values("'.$companyName.'","'.$website.'","'.$mobilephone.'","'.$telephone.'","'.$email.'","'.$qq.'","'.$note.'")';
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $GLOBALS["mysqli"]->error);
//			    throw new Exception("注册失败",ErrorCode::REGISTER_FAIL);
			}	

			$successInfo = [
				'CId'=>mysqli_insert_id($GLOBALS["mysqli"]),
				'cname' => $companyName,
				'website' => $website,
				'mobilephone' => $mobilephone,
				'telephone' => $telephone,
				'email' => $email,
				'qq' => $qq,
				'note' => $note,
				'createAt'=>$createAt
			];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 删除信息
		 * @params $id
		 * @return bool
		*/
		private function del($id){
			if(empty($id)){
				throw new Exception("id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			
			//删除指定用户
			$sql = "delete from customer where id='".$id."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $GLOBALS["mysqli"]->error);
//			    throw new Exception("注册失败",ErrorCode::REGISTER_FAIL);
			}
			$successInfo = [];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 获得信息列表
		 * @return array
		*/
		private function getList($page = 1,$size = 2){
			if($size>100){
				throw new Exception('分页大小最大为100',ErrorCode::PAGE_SIZE_TO_BIG);
			}
			$limit = ($page-1)*$size;
			$limit = $limit < 0 ? 0 : $limit;
			$sql = "select * from customer limit ".$limit." , ".$size;
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
		//这是正确的函数，先备份
//		private function getList(){
//			$sql = "select * from customer";
//			$res = $GLOBALS["mysqli"]->query($sql);
//			if (!$res) {
//			    die("sql error:\n" . $mysqli->error);
//			}
//			$infoList = array();
//			while ($rows = $res->fetch_assoc()){
//			    $count=count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小  
//			    for($i=0;$i<$count;$i++){  
//			        unset($rows[$i]);//删除冗余数据  
//			    }
//			    array_push($infoList,$rows);
//			}
//			$this->toJson($infoList,200);
//		}
		
		/**
		 * 获得单条信息
		 * @params $id
		 * @return array
		*/
		private function view($id){
			if(empty($id)){
				throw new Exception("id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			$sql = "select * from customer where id=".$id;
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
		 * 更新单条信息
		 * @params $id
		 * @return array
		*/
		private function update($id,$companyName="",$website="",$mobilephone="",$telephone="",$email="",$qq="",$note=""){
			if(empty($id)){
				throw new Exception("id不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			$sql = "update customer set cname='".$companyName."' , website='".$website."' , mobilephone='".$mobilephone."', telephone='".$telephone."', email='".$email."', qq='".$qq."', note='".$note."' where id='".$id."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $mysqli->error);
			}
			
			$successInfo = [
				'CId'=>mysqli_insert_id($GLOBALS["mysqli"]),
				'cname' => $companyName,
				'website' => $website,
				'mobilephone' => $mobilephone,
				'telephone' => $telephone,
				'email' => $email,
				'qq' => $qq,
				'note' => $note,
				'createAt'=>$createAt
			];
			
			$this->toJson($successInfo,200);
		}
		
		/**
		 * 检测这家公司是否存在
		 * @params $company
		 * @return bool
		 * 
		*/
		private function isCompanyExists($company){
			if(empty($company)){
				throw new Exception("公司名称不能为空",ErrorCode::USERNAME_CANNOT_EMPTY);
			}
			$sql = "select * from customer where cname='".$company."'";
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
	
	$info = new InfoList();
	$info->run();
?>