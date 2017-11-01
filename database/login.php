<?php
	header("Access-Control-Allow-Origin:*");
	header("Content-type: text/html; charset=utf-8"); 
	include "lib/db.php";
	
	
	class Login{
		
		private $jsonstr;
		
		private $jsonObj;
		
		private $username;
		
		private $passward;
		
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
			$this->getLoginInfo();
			$this->loginMain($this->username,$this->passward);
		}
		
		private function getLoginInfo(){
			$this->jsonstr = file_get_contents('php://input');
			$this->jsonObj = json_decode($this->jsonstr);
				
			$this->username = $this->jsonObj->username;
			$this->passward = $this->jsonObj->passward;

			//用restlet测试时用这句
//			$this->username = $this->jsonObj["username"];
//			$this->passward = $this->jsonObj["passward"];
//
//			$this->username = "admin";
//			$this->passward = "tlkj101025";
		}
		
		private function loginMain($username,$passward){
			$sql = "select * from user where username='".$username."' and passward='".$passward."'";
			$res = $GLOBALS["mysqli"]->query($sql);
			if (!$res) {
			    die("sql error:\n" . $mysqli->error);
			}
			$rows = $res->fetch_assoc();
			//count返回的是数组项目的个数
			if(count($rows)>0){
				//在服务器端保存session
				//session是一个关联数组，其中有user_id和username
				//这里有个叫session_id的东西，他是为每个账号分配的，他就相当于一把钥匙，有他才能找到对应的session
				//session放在服务器端，浏览器关闭就清除
				session_start();
				$sn = session_id();
				$_SESSION['user_id']=$rows['id'];
		        $_SESSION['username']=$rows['username'];
		        $_SESSION['ability']=$rows['ability'];
		        $rows["sessionid"]=$sn;
//		        header("HTTP/1.1 200 Success");
//				echo json_encode($rows,JSON_UNESCAPED_UNICODE);
				$this->toJson($rows,200);
			}else{
				$errorInfo = [
					"errorCode" => 400,
					"errorMessage" => "Bad Request",
				];
//				header("HTTP/1.1 400 Bad Request");
//				echo json_encode($errorInfo,JSON_UNESCAPED_UNICODE);
				$this->toJson($errorInfo,400);
			}
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

	$login = new Login();
	$login->run();
?>