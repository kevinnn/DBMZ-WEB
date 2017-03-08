<?php
// include '../../config/config.php';
// include __ROOT__.'/util/global.php';
// include __ROOT__.'/controller/yungouController.php';
// echo yungouController::process();
// exit();
// print_r(yungouModel::enough(25));
// include '../../config/config.php';
// include __ROOT__.'/controller/yungouController.php';
// echo yungouController::process();
// exit();
// include __ROOT__.'/controller/winOrderController.php';
// print_r(winOrderController::confirmAddress(1));
// print_r(winOrderController::getWinOrderById(1));
// print_r(winOrderController::getWinOrderAddress(1));
// include __ROOT__.'/controller/userController.php';
// print_r(userController::getUser());

// include __ROOT__.'/controller/cashierController.php';
// print_r(cashierController::getDetailByCashier(1));
// $a = "18819451370";
// setGlobal($a,1);
// isExist($a);
// $url = 'test.dangke.co/wxPay/paySuccess';
// $post_data = array('cashierid'=>'000020201605061720432486','fee'=>0.01);
// $ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

// $output = curl_exec($ch);
// print_r($output);
// curl_close($ch);
// $baseUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
// echo $baseUrl;
// exit();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<input name="phoneNumber" type="number" id="phone"></input>
	<input name="password" type="password" id="password"></input>
	<button onclick="login()">登录</button>
	<button onclick="checkOn()">验证</button>
	<button onclick="logout()">退出</button>
	<button onclick="getData()">获取数据</button>
	<button onclick="addData()">插入数据</button>
	<button onclick="removeData()">删除数据</button>
	<button onclick="cashier()">收银</button>
	<button onclick="pay()">支付</button>
	<button onclick="updateData()">修改</button>
	<script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
	<script type="text/javascript">
		function login() {
			var phoneNumber = document.getElementById('phone').value;
			var password = document.getElementById('password').value;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					console.log(xhttp.responseText);
				}
			};
			xhttp.open("POST", "/user/login", true);
			// xhttp.send();
			xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhttp.send("phoneNumber="+phoneNumber+"&password="+password);
		}
		function checkOn() {
			// var phoneNumber = document.getElementById('phone').value;
			// var password = document.getElementById('password').value;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					console.log(xhttp.responseText);
				}
			};
			xhttp.open("GET", "/user/isLogin", true);
			xhttp.send();
			// xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			// xhttp.send("phoneNumber="+phoneNumber+"&password="+password);
		}
		function logout() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					console.log(xhttp.responseText);
				}
			};
			xhttp.open("GET", "/user/logout", true);
			xhttp.send();
		}
		function getData() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function () {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					console.log(xhttp.responseText);
				}
			};
			xhttp.open("GET", "/shoppingCart/list", true);
			xhttp.send();
		}
		function addData() {
			$.ajax({
				url: "/address/addMobile",
				type: "POST",
				data: {
					provinceID : '440000',
					cityID : '440100',
					areaID : '440113',
					street : '华南理工大学C12',
					receiver : 'www',
					phoneNumber : '18819451333',
					status : 1
				},
				success: function (data) {
					console.log(data);
				}
			});
		}
		function removeData() {
			$.ajax({
				url: "/shoppingCart/removeLot",
				type: "POST",
				data: {
					ids:[51,52]
				},
				success: function (data) {
					console.log(data);
				}
			});
		}
		function pay() {
			$.ajax({
				url: "/pay",
				type: "POST",
				data: {
					cashierid: "201604141112111858"
				},
				success: function (data) {
					console.log(data);
				},
				error: function (data) {
					console.log(data);
				}
			})
		}
		function cashier() {
			$.ajax({
				url: "/cashier/add",
				type: "POST",
				data: {
					orders: [
						{
							productId: 1,
							yungouId: 1,
							count: 123
						},
						{
							productId: 2,
							yungouId: 2,
							count: 124
						}
					]
				},
				success: function (data) {
					console.log(data);
				},
				error: function (data) {
					console.log(data);
				}
			})
		}
		function updateData() {
			$.ajax({
				url: "/address/update",
				type: "POST",
				data: {
					address: {
						id: '51',
						areaID: '440113',
						cityID: '440100',
						idCode: '',
						phoneNumber: '18819451370',
						postCode: null,
						provinceID: '440000',
						receiver: 'STONE',
						status: '0',
						street: 'SYSU'
					}
						
				},
				success: function (data) {
					console.log(data);
				},
				error: function (data) {
					console.log(data);
				}
			})
		}
	</script>
</body>
</html>