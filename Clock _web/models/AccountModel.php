<?php 
	trait AccountModel{
		public function modelRegister(){
			$name = $_POST["name"];
			$email = $_POST["email"];
			$address = $_POST["address"];
			$phone = $_POST["phone"];
			$password = $_POST["password"];
			$password = md5($password);
			$conn = Connection::getInstance();
			//kiem tra neu email chua ton tai thi insert ban ghi
			$queryCheck = $conn->prepare("select email from customers where email=:var_email");
			$queryCheck->execute(["var_email"=>$email]);
			if($queryCheck->rowCount() > 0)
				header("location:index.php?controller=account&action=register&notify=error");
			else{
				//insert ban ghi
				$query = $conn->prepare("insert into customers set name=:var_name, email=:var_email, address=:var_address, phone=:var_phone, password=:var_password");
				$query->execute(["var_name"=>$name,"var_email"=>$email,"var_address"=>$address,"var_phone"=>$phone,"var_password"=>$password]);
				header("location:index.php?controller=account&action=login");
			}
		}
		public function modelLogin(){
			$email = $_POST["email"];
			$password = $_POST["password"];
			$password = md5($password);
			$conn = Connection::getInstance();
			$query = $conn->prepare("select id, email,password from customers where email=:var_email");
			$query->execute(["var_email"=>$email]);
			if($query->rowCount() >0){
				//lay mot ban ghi
				$result = $query->fetch();
				if($password == $result->password){
					$_SESSION['customer_id'] = $result->id;
					$_SESSION['customer_email'] = $result->email;
					header("location:index.php");
				}else
					header("location:index.php?controller=account&action=login");
			}
			else
				header("location:index.php?controller=account&action=register");
		}

		public function modelDetailAccount(){
			$email = isset($_GET["email"]) && $_GET["email"] > 0 ? $_GET["email"] : 0;

			//lay bien ket noi csdl
			$conn = Connection::getInstance();
			//thuc hien truy van
			$query = $conn->query("select * from customers");
			//tra ve so luong ban ghi
			return $query->fetch();
		}
		public function modelLogout(){
			//huy các biến session
			unset($_SESSION["customer_id"]);
			unset($_SESSION["customer_email"]);
			header("location:index.php");
		}
	}
 ?>