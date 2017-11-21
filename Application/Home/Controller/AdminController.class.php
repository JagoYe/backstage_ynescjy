<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
	// 页面跳转
    public function index(){
    	if ($this -> loginConfirm()) {
    		$this -> display("Admin/Index/index");
    	}else {
    		$this -> display("Admin/Login/index");
    	}
    }
	public function toAccountManage(){
    	if ($this -> loginConfirm()) {
    		$pageName = $_GET['pageName'];
	    	$params = array(
	    		'pageName' => $pageName
	    	);
	    	$this -> assign($params);
    		$this -> display("Admin/AccountManage/index");
    	}else {
    		$this -> display("Admin/Login/index");
    	}
    }
    public function toInformation(){
    	if ($this -> loginConfirm()) {
    		$isChild = $_GET['isChild'];
    		if ($isChild) {
    			$page = $_GET['childName'];
    		}else {
    			$page = 'index';
    		}
    		$pageName = $_GET['pageName'];
	    	$params = array(
	    		'pageName' => $pageName
	    	);
	    	$this -> assign($params);
    		$this -> display("Admin/Information/".$page);
    	}else {
    		$this -> display("Admin/Login/index");
    	}
    }
    public function toPendingVehicle() {
    	if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/PendingVehicle/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }
    public function toDownload() {
        if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/Download/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }
    public function toFrontend() {
        if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/Frontend/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }
    public function toHotel() {
        if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/Hotel/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }
    public function toCar() {
        if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/Car/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }
    public function toCarSale() {
        if ($this -> loginConfirm()) {
            $isChild = $_GET['isChild'];
            if ($isChild) {
                $page = $_GET['childName'];
            }else {
                $page = 'index';
            }
            $pageName = $_GET['pageName'];
            $params = array(
                'pageName' => $pageName
            );
            $this -> assign($params);
            $this -> display("Admin/CarSale/".$page);
        }else {
            $this -> display("Admin/Login/index");
        }
    }

    //支付接口回调接收
    public function wecahtPayBack() {
        
    }



    // car sale ------------------------------------------------------------------------
    public function carSaleStatusUpdateAjaxReturn() {
        header('content-type:text/html;charset=utf-8');
        $CarSale = D('Car_sale');

        // 当前时间戳
        // date_default_timezone_set('PRC');
        $curTimeStamp = time();

        $result = $CarSale -> select();
        foreach ($result as $key => $val) {
            if ($curTimeStamp >= (int)$val['starting_timestamp']) {
                if ($curTimeStamp <= (int)$val['ending_timestamp']) {
                    $CarSale -> where(array('id' => $val['id'])) -> save(array('status' => 1));
                }else {
                    $CarSale -> where(array('id' => $val['id'])) -> save(array('status' => 2));
                }
            }
        }

        $this -> ajaxReturn($curTimeStamp);
    }

    public function carSaleSelectById($id) {
        header('content-type:text/html;charset=utf-8');
        $CarSale = D('Car_sale');
        $CarSaleOffer = D('Car_sale_offer');

        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $newArr = array();

        $result = $CarSale -> where(array('car_id' => $id)) -> order('id desc') -> select();
        foreach ($result as $key1 => $val1) {
            $singleCar = $Car -> where(array('id' => $val1['car_id'])) -> select();
            $singleCar = $singleCar[0];
            
            //获取car brand
            $brandId = $singleCar['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $singleCar['brand'] = $brandArr;

            //获取car style
            $styleId = $singleCar['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $singleCar['brand']['style'] = $styleArr['name'];

            //car sale信息
            $singleCar['car_sale'] = $val1;

            // car sale offer 拍车人信息
            $singleCar['car_sale_offer'] = $CarSaleOffer -> where(array('car_sale_id' => $val1['id'])) -> order('id desc') -> select();

            array_push($newArr, $singleCar);
        }

        return $newArr;
    }

    public function carSaleDelete() {
        header('content-type:text/html;charset=utf-8');
        $CarSale = D('Car_sale');

        //数据接收
        $id = $_GET['deleteId'];

        //删除
        if ($CarSale -> where(array('car_id' => $id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'isChild' => true,
                'childName' => 'car_sale_pending',
                'info' => '停止拍卖车辆成功!',
                'pageName' => 'car_sale'
            );
            $this -> redirect('Admin/toCarSale', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'isChild' => true,
                'childName' => 'car_sale_pending',
                'info' => '停止拍卖车辆失败!',
                'pageName' => 'car_sale'
            );
            $this -> redirect('Admin/toCarSale', $params);
        }
    }

    public function carSalePendingByStatus($status) {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $Dao = D('Car_sale');
        $newArr = array();

        $result = $Dao -> where(array('status' => $status)) -> order('id desc') -> select();
        foreach ($result as $key1 => $val1) {
            $singleCar = $Car -> where(array('id' => $val1['car_id'])) -> select();
            $singleCar = $singleCar[0];
            
            //获取car brand
            $brandId = $singleCar['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $singleCar['brand'] = $brandArr;

            //获取car style
            $styleId = $singleCar['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $singleCar['brand']['style'] = $styleArr['name'];

            //car sale信息
            $singleCar['car_sale'] = $val1;

            array_push($newArr, $singleCar);
        }

        return $newArr;
    }

    public function carSaleFinished() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $Dao = D('Car_sale');
        $newArr = array();        

        $result2 = $Dao -> where(array('status' => '2')) -> order('id desc') -> select();
        foreach ($result2 as $key1 => $val1) {
            $singleCar = $Car -> where(array('id' => $val1['car_id'])) -> select();
            $singleCar = $singleCar[0];
            
            //获取car brand
            $brandId = $singleCar['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $singleCar['brand'] = $brandArr;

            //获取car style
            $styleId = $singleCar['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $singleCar['brand']['style'] = $styleArr['name'];

            //car sale信息
            $singleCar['car_sale'] = $val1;

            array_push($newArr, $singleCar);
        }

        return $newArr;
    }

    public function carSalePending() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $Dao = D('Car_sale');
        $newArr = array();        

        $result2 = $Dao -> where(array('status' => '1')) -> order('id desc') -> select();
        foreach ($result2 as $key1 => $val1) {
            $singleCar = $Car -> where(array('id' => $val1['car_id'])) -> select();
            $singleCar = $singleCar[0];
            
            //获取car brand
            $brandId = $singleCar['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $singleCar['brand'] = $brandArr;

            //获取car style
            $styleId = $singleCar['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $singleCar['brand']['style'] = $styleArr['name'];

            //car sale信息
            $singleCar['car_sale'] = $val1;

            array_push($newArr, $singleCar);
        }

        $result1 = $Dao -> where(array('status' => '0')) -> order('id desc') -> select();
        foreach ($result1 as $key1 => $val1) {
            $singleCar = $Car -> where(array('id' => $val1['car_id'])) -> select();
            $singleCar = $singleCar[0];
            
            //获取car brand
            $brandId = $singleCar['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $singleCar['brand'] = $brandArr;

            //获取car style
            $styleId = $singleCar['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $singleCar['brand']['style'] = $styleArr['name'];

            //car sale信息
            $singleCar['car_sale'] = $val1;

            array_push($newArr, $singleCar);
        }

        return $newArr;
    }

    public function carSaleAdd() {
        header('content-type:text/html;charset=utf-8');
        $Dao = D('car_sale');

        //日期转换时间戳
        $startingTimeStamp = strtotime($_POST['starting_time']);
        $endingTimeStamp = strtotime($_POST['ending_time']);
        $curTime = time();

        // var_dump($curTime);
        // var_dump($startingTimeStamp);
        // die();

        if ($curTime < $startingTimeStamp) {
            //提交参数
            $data = array(
                'car_id' => $_POST['car_id'],
                'starting_price' => $_POST['starting_price'],
                'evaluate_price' => $_POST['evaluate_price'],
                'starting_time' => $_POST['starting_time'],
                'ending_time' => $_POST['ending_time'],
                'amount_of_increase' => $_POST['amount_of_increase'],
                'starting_timestamp' => $startingTimeStamp,
                'ending_timestamp' => $endingTimeStamp,
                'status' => '0'
            );
            
            if ($Dao -> data($data) -> add()) {
                $params = array(
                    'status' => 'success',
                    'info' => '拍卖车辆添加成功!',
                    'pageName' => 'car_sale'
                );
                $this -> redirect('Admin/toCarSale', $params);
            }else {
                $params = array(
                    'status' => 'fail',
                    'info' => '拍卖车辆添加失败!',
                    'pageName' => 'car_sale'
                );
                $this -> redirect('Admin/toCarSale', $params);
            }
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '拍卖时间不能小时当前时间!',
                'pageName' => 'car_sale'
            );
            $this -> redirect('Admin/toCarSale', $params);
        }
    }

    public function carSaleByStatusAjaxReturn() {
        header('content-type:text/html;charset=utf-8');
        $Dao = D('Car_sale');
        $condition = array();

        if ($_POST['status'] == '-1') {
            
        }else {
            $condition = array(
                'status' => $_POST['status']
            );
        }

        $result = $Dao -> where($condition) -> order('id desc') -> select();
        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data'=> $result
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'code'=> '400',
                'status'=> 'fail'
            );
            $this -> ajaxReturn($param);
        }
    }
    // car sale ------------------------------------------------------------------------ end

    // car hot -------------------------------------------------------------------------
    public function carHotSelectByCategory($category) {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $CarHot = D('Car_hot');
        $newArr = array();

        $result = $CarHot -> where(array('category' => $category)) -> select();
        $car_id_arr = explode(' | ', $result[0]['car_id_arr']);

        foreach($car_id_arr as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            if ($brand_name) {
                $brand_name = $brand_name[0]['name'];
                $newArr[$key]['brand_name'] = $brand_name;
            }

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            if ($style_name) {
                $style_name = $style_name[0]['name'];
                $newArr[$key]['style_name'] = $style_name;   
            }
        }

        
        // $this -> ajaxReturn($param);
        return $newArr;
    }

    public function carHotAdd() {
        header('content-type:text/html;charset=utf-8');
        $CarHot = D('Car_hot');

        $category = $_POST['category'];
        $car_id = $_POST['car_id'];

        $result = $CarHot -> where(array('category' => $category)) -> select();
        if ($result[0]['car_id_arr'] == '') {
            $car_id_arr = $car_id;
        }else {
            $car_id_arr = explode(' | ', $result[0]['car_id_arr']);

            array_push($car_id_arr, $car_id);
            $car_id_arr = implode(' | ', $car_id_arr);
        }

        if ($CarHot -> where(array('category' => $category)) -> save(array('car_id_arr' => $car_id_arr))) {
            $params = array(
                'status' => 'success',
                'info' => '热门车辆添加成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '热门车辆添加失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carHotDelete() {
        header('content-type:text/html;charset=utf-8');
        $CarHot = D('Car_hot');

        $category = $_POST['category'];
        $car_id = $_POST['deleteId'];

        $condition = array(
            'category' => $category
        );

        $result = $CarHot -> where($condition) -> select();
        $car_id_arr = explode(' | ', $result[0]['car_id_arr']);

        foreach ($car_id_arr as $key => $val) {
            if ($val == $car_id) {
                unset($car_id_arr[$key]);
            }
        }

        $car_id_arr = implode(' | ', $car_id_arr);

        if ($CarHot -> where($condition) -> save(array('car_id_arr' => $car_id_arr))) {
            $params = array(
                'status' => 'success',
                'info' => '热门车辆删除成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '热门车辆删除失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carHotSelectByAllow($category) {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $CarHot = D('Car_hot');

        // hot
        $car_hot_arr = $CarHot -> where(array('category' => $category)) -> select();
        $car_hot_id_arr = explode(' | ', $car_hot_arr[0]['car_id_arr']);

        // car
        $car_arr = $Car -> where(array('category' => $category)) -> select();
        $car_id_arr = array();

        foreach ($car_arr as $key => $val) {
            array_push($car_id_arr, $val['id']);
        }

        foreach ($car_id_arr as $key => $val) {
            foreach ($car_hot_id_arr as $key1 => $val1) {
                if ($val1 == $val) {
                    unset($car_id_arr[$key]);
                }
            }
        }

        //汽车详情
        $newArr = array();
        foreach ($car_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            $simpleCar = $simpleCar[0];
            array_unshift($newArr, $simpleCar);
        }

        foreach($newArr as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            $brand_name = $brand_name[0]['name'];
            $newArr[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $newArr[$key]['style_name'] = $style_name;
        }

        // hot 详情
        $newHotArr = array();
        foreach ($car_hot_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            if ($simpleCar) {
                $simpleCar = $simpleCar[0];
                array_unshift($newHotArr, $simpleCar);
            }
        }

        foreach($newHotArr as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            if ($brand_name) {
                $brand_name = $brand_name[0]['name'];
                $newHotArr[$key]['brand_name'] = $brand_name;
            }

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            if ($style_name) {
                $style_name = $style_name[0]['name'];
                $newHotArr[$key]['style_name'] = $style_name;   
            }
        }

        //返回结果

        $data = array(
            'allow_add' => $newArr,
            'already' => $newHotArr
        );

        return $data;
    }

    public function carHotSelectByAllowAjaxReturn() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $CarHot = D('Car_hot');

        $category = $_POST['category'];

        // hot
        $car_hot_arr = $CarHot -> where(array('category' => $category)) -> select();
        $car_hot_id_arr = explode(' | ', $car_hot_arr[0]['car_id_arr']);

        // car
        $car_arr = $Car -> where(array('category' => $category)) -> select();
        $car_id_arr = array();

        foreach ($car_arr as $key => $val) {
            array_push($car_id_arr, $val['id']);
        }

        foreach ($car_id_arr as $key => $val) {
            foreach ($car_hot_id_arr as $key1 => $val1) {
                if ($val1 == $val) {
                    unset($car_id_arr[$key]);
                }
            }
        }

        //汽车详情
        $newArr = array();
        foreach ($car_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            $simpleCar = $simpleCar[0];
            array_unshift($newArr, $simpleCar);
        }

        foreach($newArr as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            $brand_name = $brand_name[0]['name'];
            $newArr[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $newArr[$key]['style_name'] = $style_name;
        }

        // hot 详情
        $newHotArr = array();
        foreach ($car_hot_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            if ($simpleCar) {
                $simpleCar = $simpleCar[0];
                array_unshift($newHotArr, $simpleCar);
            }
        }

        foreach($newHotArr as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            if ($brand_name) {
                $brand_name = $brand_name[0]['name'];
                $newHotArr[$key]['brand_name'] = $brand_name;
            }

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            if ($style_name) {
                $style_name = $style_name[0]['name'];
                $newHotArr[$key]['style_name'] = $style_name;   
            }
        }

        //返回结果

        $data = array(
            'allow_add' => $newArr,
            'already' => $newHotArr
        );

        $this -> ajaxReturn($data);
    }

    // car
    public function carAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $imageUrl = '';

        // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $params = array(
                'status' => 'fail',
                'info' => '图片上传失败!',
                'pageName' => 'hotel'
            );
            // $this -> redirect('Admin/toHotel', $params);
            // var_dump('上传失败');
        }else{// 上传成功 获取上传文件信息
            foreach($info as $key => $val) {
                if ($imageUrl == '') {
                    $imageUrl = '/Upload/photos/'.$val['savepath'].$val['savename'];
                }else {
                    $imageUrl = $imageUrl . ' | ' . '/Upload/photos/'.$val['savepath'].$val['savename'];
                }
            }
            $_POST['images'] = $imageUrl;
        }

        //城市 id、name 分离
        $cityArr = explode(' ', $_POST['city']);
        $city_id = $cityArr[0];
        $city_name = $cityArr[1];
        $_POST['city_id'] = $city_id;
        $_POST['city_name'] = $city_name;

        //加入数据库
        if ($Car -> data($_POST) -> add()) {
            $params = array(
                'status' => 'success',
                'info' => '车辆添加成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '车辆添加失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    //汽车管理 -> 车辆查看 -> 保存
    public function carUpdate(){
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $carId = $_POST['viewId'];

        $cityArr = explode(' ', $_POST['city']);
        $city_id = $cityArr[0];
        $city_name = $cityArr[1];
        $_POST['city_id'] = $city_id;
        $_POST['city_name'] = $city_name;
        
        $Car -> where(array('id' => $carId)) -> save($_POST);
        $params = array(
            'status' => 'success',
            'info' => '车辆信息更新成功!',
            'pageName' => 'car',
            'isChild' => 'true',
            'childName' => 'view_car',
            'pageId' => $carId
        );
        $this -> redirect('Admin/toCar', $params);
    }

    public function carSelectAll(){
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        // $newArr = array();

        $result = $Car -> order('id desc') -> select();

        foreach ($result as $key => $val) {
            //获取car brand
            $brandId = $val['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $result[$key]['brand'] = $brandArr;

            //获取car style
            $styleId = $val['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $result[$key]['brand']['style'] = $styleArr['name'];
        }
        
        return $result;
    }

    public function carDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $carId = $_GET['deleteId'];

        $result = $Car -> where(array('id' => $carId)) -> select();
        $result = $result[0];
        $imagesArr = explode(' | ', $result['images']);

        //删除图片
        foreach($imagesArr as $key => $val) {
            unlink('.'.$val);
        }

        //删除信息
        if ($Car -> where(array('id' => $carId)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '车辆信息删除成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '车辆信息删除失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carSelectById($carId) {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        // $newArr = array();

        $result = $Car -> where(array('id' => $carId)) -> select();

        foreach ($result as $key => $val) {
            //获取car brand
            $brandId = $val['brand_id'];
            $brandArr = $CarBrand -> where(array('id' => $brandId)) -> select();
            $brandArr = $brandArr[0];
            $result[$key]['brand'] = $brandArr;

            //获取car style
            $styleId = $val['style_id'];
            $styleArr = $CarStyle -> where(array('id' => $styleId)) -> select();
            $styleArr = $styleArr[0];
            $result[$key]['brand']['style'] = $styleArr['name'];
        }
        
        return $result;
    }

    public function carBrandSelectAll() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');

        $result = $CarBrand -> order('id desc') -> select();
        return $result;
    }

    public function carStyleSelectByIdAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $brandId = $_POST['brand_id'];
        $newArr = array();

        //获取brand
        $brand = $CarBrand -> where(array('id' => $brandId)) -> select();
        $brand = $brand[0];
        $styleIdArr = $brand['style_id'];
        $styleIdArr = explode('/', $styleIdArr);

        //循环获取当前brand下的style
        foreach($styleIdArr as $key => $val) {
            $style = $CarStyle -> where(array('id' => $val)) -> select();
            $style = $style[0];

            //style加入newArr
            array_push($newArr, $style);
        }
        $this -> ajaxReturn($newArr);
    }

    public function carBrandNameUpdateAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $id = $_POST['id'];
        $name = $_POST['name'];

        if ($CarBrand -> where(array('id' => $id)) -> save(array('name' => $name))) {
            $param = array(
                'status' => 'success'
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'status' => 'fail'
            );
            $this -> ajaxReturn($param);
        }
    }

    public function carStyleSelectByBrandId($brandId) {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $newArr = array();

        //获取brand
        $brand = $CarBrand -> where(array('id' => $brandId)) -> select();
        $brand = $brand[0];
        $styleIdArr = $brand['style_id'];
        $styleIdArr = explode('/', $styleIdArr);

        //循环获取当前brand下的style
        foreach($styleIdArr as $key => $val) {
            $style = $CarStyle -> where(array('id' => $val)) -> select();
            $style = $style[0];

            //style加入newArr
            array_push($newArr, $style);
        }
        return $newArr;
    }
    public function carStyleSelectByBrandIdAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $newArr = array();
        $brandId = $_POST['brand_id'];

        //获取brand
        $brand = $CarBrand -> where(array('id' => $brandId)) -> select();
        $brand = $brand[0];
        $styleIdArr = $brand['style_id'];
        $styleIdArr = explode('/', $styleIdArr);

        //循环获取当前brand下的style
        foreach($styleIdArr as $key => $val) {
            $style = $CarStyle -> where(array('id' => $val)) -> select();
            $style = $style[0];

            //style加入newArr
            array_push($newArr, $style);
        }
        $this -> ajaxReturn($newArr);
    }

    //根据styleId 获取style
    public function carStyleSelectById($styleId) {
        header("Content-Type:text/html; charset=utf-8");
        $CarStyle = D('Car_style');

        $result = $CarStyle -> where(array('id' => $styleId)) -> select();

        return $result;
    }

    //车辆查看->图片添加
    public function carImageAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $carId = $_GET['id'];

        $result = $Car -> where(array('id' => $carId)) -> select();
        $result = $result[0];
        $imagesArr = explode(' | ', $result['images']);

         // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $params = array(
                'status' => 'fail',
                'info' => '图片上传失败!',
                'pageName' => 'hotel'
            );
            // $this -> redirect('Admin/toHotel', $params);
            var_dump('上传失败');
        }else{// 上传成功 获取上传文件信息
            foreach($info as $key => $val) {
                $imageUrl = '/Upload/photos/'.$val['savepath'].$val['savename'];
                array_push($imagesArr, $imageUrl);
            }
        }

        $imagesStr = implode(' | ', $imagesArr);
        if ($Car -> where(array('id' => $carId)) -> save(array('images' => $imagesStr))) {
            $params = array(
                'status' => 'success',
                'info' => '图片添加成功!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '图片添加失败!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    //汽车管理 -> 车辆查看 -> 图片删除
    public function carImageDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $carId = $_POST['imageDeleteId'];
        $imageUrl = $_POST['imageDeleteUrl'];

        $result = $Car -> where(array('id' => $carId)) -> select();
        $result = $result[0];
        $imagesArr = explode(' | ', $result['images']);

        unlink('.'.$imageUrl);

        foreach($imagesArr as $key => $val) {
            if ($imageUrl == $val) {
                unset($imagesArr[$key]);
            }
        }

        $imagesArr = implode(' | ', $imagesArr);
        if ($Car -> where(array('id' => $carId)) -> save(array('images' => $imagesArr))) {
            $params = array(
                'status' => 'success',
                'info' => '图片删除成功!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '图片更新失败!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    //汽车管理 -> 车辆查看 -> 图片编辑
    public function carImageEdit() {
        header("Content-Type:text/html; charset=utf-8");
        $Car = D('Car');
        $carId = $_GET['id'];
        $imageUrl = $_POST['imageEditUrl'];

        $result = $Car -> where(array('id' => $carId)) -> select();
        $result = $result[0];
        $imagesArr = explode(' | ', $result['images']);

         // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $params = array(
                'status' => 'fail',
                'info' => '图片上传失败!',
                'pageName' => 'hotel'
            );
            // $this -> redirect('Admin/toHotel', $params);
            var_dump('上传失败');
        }else{// 上传成功 获取上传文件信息
            foreach($info as $key => $val) {
                $newImage = '/Upload/photos/'.$val['savepath'].$val['savename'];
            }
        }

        unlink('.'.$imageUrl);

        foreach($imagesArr as $key => $val) {
            if ($imageUrl == $val) {
                $imagesArr[$key] = $newImage;
            }
        }

        $imagesArr = implode(' | ', $imagesArr);
        if ($Car -> where(array('id' => $carId)) -> save(array('images' => $imagesArr))) {
            $params = array(
                'status' => 'success',
                'info' => '图片更新成功!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '图片更新失败!',
                'pageName' => 'car',
                'isChild' => 'true',
                'childName' => 'view_car',
                'pageId' => $carId
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carBrandAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $brand_name = $_POST['brand_name'];
        $style_name = $_POST['style_name'];
        $brand_initial = $_POST['initial'];

        $result = $CarBrand -> where(array('name' => $brand_name)) -> select();
        if ($result) {
            $params = array(
                'status' => 'fail',
                'info' => '该品牌已存在，请勿重复添加!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            if ($brand_id = $CarBrand -> data(array('name' => $brand_name, 'initial' => $brand_initial)) -> add()) {
                //添加style
                $style_id = $CarStyle -> data(array('name' => $style_name)) -> add();
                
                //修改brand style_id
                $CarBrand -> where(array('id' => $brand_id)) -> save(array('style_id' => $style_id));

                $params = array(
                    'status' => 'success',
                    'info' => '品牌添加成功!',
                    'pageName' => 'car'
                );
                $this -> redirect('Admin/toCar', $params);
            }else {
                $params = array(
                    'status' => 'fail',
                    'info' => '品牌添加失败!',
                    'pageName' => 'car'
                );
                $this -> redirect('Admin/toCar', $params);
            }
        }
    }

    public function carBrandUpdate() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $id = $_POST['brand_id'];
        $name = $_POST['brand_name'];
        $initial = $_POST['initial'];

        if ($CarBrand -> where(array('id' => $id)) -> save(array('name' => $name, 'initial' => $initial))) {
            $params = array(
                'status' => 'success',
                'info' => '品牌更新成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'success',
                'info' => '无更改!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carBrandDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');

        $id = $_GET['deleteId'];
        $result = $CarBrand -> where(array('id' => $id)) -> select();
        $result = $result[0];

        $styleIdArr = $result['style_id'];
        $styleIdArr = explode('/', $styleIdArr);

        //删除style
        foreach($styleIdArr as $key => $val) {
            $CarStyle -> where(array('id' => $val)) -> delete();
        }

        //删除brand
        if ($CarBrand -> where(array('id' => $id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '品牌删除成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '品牌删除失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carStyleAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');

        $brand_id = $_POST['brand_id'];
        $style_name = $_POST['style_name'];

        $brandArr = $CarBrand -> where(array('id' => $brand_id)) -> select();
        $brandArr = $brandArr[0];

        $brandArr = explode('/', $brandArr['style_id']);

        if ($nowId = $CarStyle -> data(array('name' => $style_name)) -> add()) {
            array_push($brandArr, $nowId);
            $brandArr = implode('/', $brandArr);
            if ($CarBrand -> where(array('id' => $brand_id)) -> save(array('style_id' => $brandArr))) {
                $params = array(
                    'status' => 'success',
                    'info' => '车型添加成功!',
                    'pageName' => 'car'
                );
                $this -> redirect('Admin/toCar', $params);
            }else {
                $params = array(
                    'status' => 'fail',
                    'info' => '品牌车型ID添加失败!',
                    'pageName' => 'car'
                );
                $this -> redirect('Admin/toCar', $params);
            }
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '车型添加失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carStyleDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');

        $brand_id = $_GET['delete_brand_id'];
        $style_id = $_GET['deleteId'];
        
        $result = $CarBrand -> where(array('id' => $brand_id)) -> select();
        $result = $result[0];
        $styleIdArr = explode('/', $result['style_id']);
        foreach ($styleIdArr as $key => $val) {
            if ($style_id == $val) {
                unset($styleIdArr[$key]);
            }
        }
        $styleIdArr =  implode('/', $styleIdArr);
        $CarBrand -> where(array('id' => $brand_id)) -> save(array('style_id' => $styleIdArr));


        if ($CarStyle -> where(array('id' => $style_id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '车型删除成功!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '车型删除失败!',
                'pageName' => 'car'
            );
            $this -> redirect('Admin/toCar', $params);
        }
    }

    public function carStyleNameUpdateAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $id = $_POST['id'];
        $brand_id = $_POST['delete_brand_id'];
        $name = $_POST['name'];

        $result = $CarBrand -> where(array('id' => $brand_id)) -> select();
        $result = $result[0];

        $styleIdArr = explode('/', $result['style_id']);
        foreach($styleIdArr as $key => $val) {
            if ($val == $id) {
                unset($styleIdArr[$key]);
                // var_dump($styleIdArr);
            }
        }

        $styleIdArr = implode('/', $styleIdArr);
        $CarBrand -> where(array('id' => $brand_id)) -> save(array('style_id' => $styleIdArr));


        if ($CarStyle -> where(array('id' => $id)) -> save(array('name' => $name))) {
            $param = array(
                'code' => '200',
                'status' => 'success'
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'code' => '400',
                'status' => 'fail'
            );
            $this -> ajaxReturn($param);
        }
    }

    // 待发布车辆 Pending Vehicle -----------------------------------------------------------------
    public function pendingVehicleByStatusAjaxReturn() {
        header('content-type:text/html;charset=utf-8');
        $Dao = D('Pending_vehicle');
        $condition = array();

        if ($_POST['status'] == '-1') {
            
        }else {
            $condition = array(
                'status' => $_POST['status']
            );
        }

        $result = $Dao -> where($condition) -> order('id desc') -> select();
        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data'=> $result
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'code'=> '400',
                'status'=> 'fail'
            );
            $this -> ajaxReturn($param);
        }
    }

    public function pendingVehicleStatusUpdate() {              //仅限 待发布 -> 已完成
        header("Content-Type:text/html; charset=utf-8");
        $PendingVehicle = D('Pending_vehicle');

        //数据接收
        $car_id = $_GET['deleteId'];

        //更新
        if ($PendingVehicle -> where(array('id' => $car_id)) -> save(array('status' => 1))) {
            $params = array(
                'status' => 'success',
                'info' => '待发布车辆-状态更新成功!',
                'pageName' => 'pending_vehicle'
            );
            $this -> redirect('Admin/toPendingVehicle', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '待发布车辆-状态更新失败!',
                'pageName' => 'pending_vehicle'
            );
            $this -> redirect('Admin/toPendingVehicle', $params);
        }

    }

    public function pendingVehicleDeleteById() {
        header("Content-Type:text/html; charset=utf-8");
        $PendingVehicle = D('Pending_vehicle');

        //数据接收
        $car_id = $_GET['deleteId'];

        //删除
        if ($PendingVehicle -> where(array('id' => $car_id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '待发布车辆删除成功!',
                'pageName' => 'pending_vehicle'
            );
            $this -> redirect('Admin/toPendingVehicle', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '待发布车辆删除失败!',
                'pageName' => 'pending_vehicle'
            );
            $this -> redirect('Admin/toPendingVehicle', $params);
        }
    }

    public function pendingVehicleNew() {
        header("Content-Type:text/html; charset=utf-8");
        $PendingVehicle = D('Pending_vehicle');

        $result = $PendingVehicle -> where(array('status' => 0)) -> select();
        return count($result);
    }

    public function pendingVehicleSelectById($id) {
        header("Content-Type:text/html; charset=utf-8");
        $PendingVehicle = D('Pending_vehicle');

        $result = $PendingVehicle -> where(array('id' => $id)) -> select();
        return $result;
    }

    public function pendingVehicleSelectByStatusAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $PendingVehicle = D('Pending_vehicle');

        //数据接收
        $status = $_POST['status'];

        //全部
        // if ($status == '0') {
        //     $PendingVehicle =
        // }
    }
    // 待发布车辆 Pending Vehicle ----------------------------------------------------------------- end







































    // hotel add
    public function hotelAdd(){
        header("Content-Type:text/html; charset=utf-8");
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        $areaArr = explode(' ', $_POST['area']);
        $areaNumber = $areaArr[0];
        $areaName = $areaArr[1];

        // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $params = array(
                'status' => 'fail',
                'info' => '图片上传失败!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }else{// 上传成功 获取上传文件信息
            $imageUrl = '/Upload/photos/'.$info['photo']['savepath'].$info['photo']['savename'];
            $themeImageUrl = '/Upload/photos/'.$info['photo1']['savepath'].$info['photo1']['savename'];
        }


        //会员价
        $memberArr = array(); //所有会员价搜集
        $memberId = array(); //当前酒店所有member id
        foreach($_POST as $key => $val) {
            if (substr($key,0,6) == 'member') {
                array_push($memberArr, $val);
            }
        }
        $memberHandle = 1;
        foreach($memberArr as $key => $val) {
            if ($memberHandle == 1) {
                $memberName = $val;
                $memberHandle++;
            }else if ($memberHandle == 2) {
                $memberDetail = $val;   
                $memberHandle++;
            }else if ($memberHandle == 3) {
                $memberPrice = $val;
                $memberHandle = 1;
                $conditionMember = array(
                    'name' => $memberName,
                    'price' => $memberPrice,
                    'spec' => $memberDetail
                );
                //添加member，获取ID
                array_push($memberId, $HotelMember -> add($conditionMember));
            }
        }
        
        //home style
        $memberIdStr = implode('、', $memberId);
        $conditionStyle = array(
            'name' => $_POST['theme'],
            'spec' => $_POST['homestyle'],
            'price' => $_POST['price'],
            'member_id' => $memberIdStr,
            'image' => $themeImageUrl
        );
        //添加homestyle 获得homestyleId
        $conditionStyleId = $HotelHomeStyle -> add($conditionStyle);

        $conditionHotel = array(
            'area' => $areaNumber,
            'area_name' => $areaName,
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'price' => $_POST['price'],
            'image' => $imageUrl,
            'home_style_id' => $conditionStyleId
        );
        //添加酒店信息
        if ($Hotel -> add($conditionHotel)) {
            $params = array(
                'status' => 'success',
                'info' => '酒店添加成功!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '酒店添加失败!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }
    }

    //酒店更新
    public function hotelUpdate() {
        header("Content-Type:text/html; charset=utf-8");
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        // id
        $hotelId = $_POST['hotelId'];
        $hotelHomeStyleId = $_POST['theme'];
        $hotelMemberId = $_POST['member'];

        // 图片
        $result = $Hotel -> where(array('id' => $hotelId)) -> select();
        $oldHotelImage = '.'.$result[0]['image'];

        $result = $HotelHomeStyle -> where(array('id' => $hotelHomeStyleId)) -> select();
        $oldHotelHomeStyleImage = '.'.$result[0]['image'];

        $areaArr = explode(' ', $_POST['area']);
        $areaNumber = $areaArr[0];
        $areaName = $areaArr[1];

        $conditionHotel = array(
            'name' => $_POST['name'],
            'area' => $areaNumber,
            'area_name' => $areaName,
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'price' => $_POST['price']
        );

        $conditionStyle = array(
            'price' => $_POST['price'],
            'spec' => $_POST['detail']
        );

        $conditionMember = array(
            'price' => $_POST['member_price'],
            'spec' => $_POST['member_detail']
        );

        // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload -> upload();
        if(!$info) {// 上传错误提示错误信息

        }else{// 上传成功 获取上传文件信息
            foreach($info as $key => $val) {
                if ($key == 'photo') {
                    unlink($oldHotelImage);
                    $hotelImageUrl = '/Upload/photos/'.$info['photo']['savepath'].$info['photo']['savename'];
                    $conditionHotel['image'] = $hotelImageUrl;
                }
                if ($key == 'photo1') {
                    unlink($oldHotelHomeStyleImage);
                    $hotelHomeStyleImageUrl = '/Upload/photos/'.$info['photo1']['savepath'].$info['photo1']['savename'];
                    $conditionStyle['image'] = $hotelHomeStyleImageUrl;
                }
            }
        }

        //修改
        $Hotel -> where(array('id' => $hotelId)) -> save($conditionHotel);
        $HotelHomeStyle -> where(array('id' => $hotelHomeStyleId)) -> save($conditionStyle);
        $HotelMember -> where(array('id' => $hotelMemberId)) -> save($conditionMember);

        $params = array(
            'status' => 'success',
            'info' => '酒店更新成功!',
            'pageName' => 'hotel',
            'isChild' => 'true',
            'childName' => 'view_hotel',
            'pageId' => $hotelId
        );
        $this -> redirect('Admin/toHotel', $params);
    }

    //酒店删除
    public function hotelDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        $id = $_GET['deleteId'];

        //图片删除
        $result = $Hotel -> where(array('id' => $id)) -> select();
        $result = $result[0];
        $image = $result['image'];
        $image = '.'.$image;
        unlink($image);

        //Hotel delete
        $hotelResult = $Hotel -> where(array('id' => $id)) -> select();
        $homeStyleId = $hotelResult[0]['home_style_id'];
        $Hotel -> where(array('id' => $id)) -> delete();

        //homestyle delete
        $homeStyleIdArr = explode('、', $homeStyleId);
        foreach($homeStyleIdArr as $key => $val) {
            $homeStyleResult = $HotelHomeStyle -> where(array('id' => $val)) -> select();
            $homeStyleResult = $homeStyleResult[0];
            //home style 图片删除
            $image = $homeStyleResult['image'];
            $image = '.'.$image;
            unlink($image);

            $HotelHomeStyle -> where(array('id' => $homeStyleResult['id'])) -> delete();

            //homte member
            $homeMemberArr = explode('、', $homeStyleResult['member_id']);
            foreach($homeMemberArr as $key => $val) {
                $HotelMember -> where(array('id' => $val)) -> delete();
            }
        }

        $params = array(
            'status' => 'success',
            'info' => '酒店删除成功!',
            'pageName' => 'hotel'
        );
        $this -> redirect('Admin/toHotel', $params);
    }

    // select all message of hotel
    public function hotelSelectAllMessage(){
        header("Content-Type:text/html; charset=utf-8");
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');
        $newArr = array();

        $result = $Hotel -> order('id desc') -> select();
        foreach($result as $key => $val) {
            $homeStyleId = $val['home_style_id'];
            $HomeStyleArr = $HotelHomeStyle -> where(array('id' => $homeStyleId)) -> order('id desc') -> select(); //homestyle array
            array_push($newArr, $val);
            foreach($HomeStyleArr as $key1 => $val1) {
                $memberIdArr = explode('、', $val1['member_id']);
                $newArr[$key]['homeStyle']['name'] = $val1['name'];
                $newArr[$key]['homeStyle']['price'] = $val1['price'];
                $newArr[$key]['homeStyle']['spec'] = $val1['spec'];
                foreach($memberIdArr as $key2 => $val2) {
                    $memberArr = $HotelMember -> where(array('id' => $val2)) -> select(); //hotel member
                    $memberArr = $memberArr[0];
                    $newArr[$key]['homeStyle']['member']['name'] = $memberArr['name'];
                    $newArr[$key]['homeStyle']['member']['price'] = $memberArr['price'];
                    $newArr[$key]['homeStyle']['member']['spec'] = $memberArr['spec'];
                }
            }
        }

        return $newArr;
    }

    // 单独添加主题
    public function hotelHomeStyleAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $hotelId = $_POST['hotelId'];
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        //会员价
        $memberArr = array(); //所有会员价搜集
        $memberId = array(); //当前酒店所有member id
        foreach($_POST as $key => $val) {
            if (substr($key,0,6) == 'member') {
                array_push($memberArr, $val);
            }
        }
        $memberHandle = 1;
        foreach($memberArr as $key => $val) {
            if ($memberHandle == 1) {
                $memberName = $val;
                $memberHandle++;
            }else if ($memberHandle == 2) {
                $memberDetail = $val;   
                $memberHandle++;
            }else if ($memberHandle == 3) {
                $memberPrice = $val;
                $memberHandle = 1;
                $conditionMember = array(
                    'name' => $memberName,
                    'price' => $memberPrice,
                    'spec' => $memberDetail
                );
                //添加member，获取ID
                array_push($memberId, $HotelMember -> add($conditionMember));
            }
        }

        // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $params = array(
                'status' => 'fail',
                'info' => '图片上传失败!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }else{// 上传成功 获取上传文件信息
            $imageUrl = '/Upload/photos/'.$info['photo1']['savepath'].$info['photo1']['savename'];
        }
        
        //home style
        $memberIdStr = implode('、', $memberId);
        $conditionStyle = array(
            'name' => $_POST['theme'],
            'spec' => $_POST['homestyle'],
            'price' => $_POST['price'],
            'member_id' => $memberIdStr,
            'image' => $imageUrl
        );
        //添加homestyle 获得homestyleId
        $conditionStyleId = $HotelHomeStyle -> add($conditionStyle);

        $HotelArr = $Hotel -> where(array('id' => $hotelId)) -> select();
        $HotelArr = $HotelArr[0];
        $homeStyleIdStr = $HotelArr['home_style_id'];
        $homeStyleIdStr = $homeStyleIdStr . '、' . $conditionStyleId;

        if ($Hotel -> where(array('id' => $hotelId)) -> save(array('home_style_id' => $homeStyleIdStr))) {
            $params = array(
                'status' => 'success',
                'info' => '主题添加成功!',
                'pageName' => 'hotel',
                'isChild' => 'true',
                'childName' => 'view_hotel',
                'pageId' => $hotelId
            );
            $this -> redirect('Admin/toHotel', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '主题添加失败!',
                'pageName' => 'hotel',
                'isChild' => 'true',
                'childName' => 'view_hotel',
                'pageId' => $hotelId
            );
            $this -> redirect('Admin/toHotel', $params);
        }
    }

    //主题名修改
    public function hotelThemeNameUpdateAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_home_style');
        $id = $_POST['id'];
        $name = $_POST['theme_name'];

        if ($Dao -> where(array('id' => $id)) -> save(array('name' => $name))) {
            $params = array(
                'status' => 'success',
                'info' => '主题名更新成功!',
                'pageName' => 'hotel'
            );
            $this -> ajaxReturn($params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '主题名更新失败!',
                'pageName' => 'hotel'
            );
            $this -> ajaxReturn($params);
        }
    }

    //会员类别名修改
    public function hotelMemberNameUpdateAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_member');
        $id = $_POST['id'];
        $name = $_POST['member_name'];

        if ($Dao -> where(array('id' => $id)) -> save(array('name' => $name))) {
            $params = array(
                'status' => 'success',
                'info' => '会员类别名更新成功!',
                'pageName' => 'hotel'
            );
            $this -> ajaxReturn($params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '会员类别名更新失败!',
                'pageName' => 'hotel'
            );
            $this -> ajaxReturn($params);
        }
    }

    //主题删除
    public function hotelThemeDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $Hotel = D('Hotel');
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        $hotelId = $_GET['hotelId'];
        $id = $_GET['deleteId'];

        $result = $HotelHomeStyle -> where(array('id' => $id)) -> select();
        $result = $result[0];
        $memberIdArr = $result['member_id'];

        //Hotel home-style-id delete id
        $HotelArr = $Hotel -> where(array('id' => $hotelId)) -> select();
        $hotelHomeStyleIdArr = explode('、', $HotelArr[0]['home_style_id']);
        foreach($hotelHomeStyleIdArr as $key => $val) {
            if ($val == $id) {
                unset($hotelHomeStyleIdArr[$key]);
            }
        }
        $hotelHomeStyleIdArr = implode('、', $hotelHomeStyleIdArr);
        $Hotel -> where(array('id' => $hotelId)) -> save(array('home_style_id' => $hotelHomeStyleIdArr));


        //相关member删除
        $memberIdArr = explode('、', $memberIdArr);  //member id array
        foreach($memberIdArr as $key => $val) {
            $HotelMember -> where(array('id' => $val)) -> delete();
        }

        //删除homestyle
        if ($HotelHomeStyle -> where(array('id' => $id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '主题删除成功!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '主题删除失败!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }
    }

    //会员类别删除
    public function hotelMemberDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        $homeStyleId = $_GET['homeStyleId'];
        $id = $_GET['deleteId'];

        $result = $HotelHomeStyle -> where(array('id' => $homeStyleId)) -> select();
        $result = $result[0];
        $memberIdArr = $result['member_id'];

        //HotelHomeStyle member_id delete
        $HotelHomeStyleArr = $HotelHomeStyle -> where(array('id' => $homeStyleId)) -> select();
        $hotelHomeStyleIdArr = explode('、', $HotelHomeStyleArr[0]['member_id']);
        foreach($hotelHomeStyleIdArr as $key => $val) {
            if ($val == $id) {
                unset($hotelHomeStyleIdArr[$key]);
            }
        }
        $hotelHomeStyleIdArr = implode('、', $hotelHomeStyleIdArr);
        $HotelHomeStyle -> where(array('id' => $homeStyleId)) -> save(array('member_id' => $hotelHomeStyleIdArr));

        //删除member
        if ($HotelMember -> where(array('id' => $id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '会员类别删除成功!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '会员类别删除失败!',
                'pageName' => 'hotel'
            );
            $this -> redirect('Admin/toHotel', $params);
        }
    }

    //单独会员类别添加
    public function hotelMemberAdd() {
        header("Content-Type:text/html; charset=utf-8");
        $HotelHomeStyle = D('Hotel_home_style');
        $HotelMember = D('Hotel_member');

        // id
        $hotelId = $_POST['hotelId'];
        $homeStyleId = $_POST['theme_member'];
        
        $memberArr = array(); //所有会员价搜集
        $memberId = array(); //当前酒店所有member id
        foreach($_POST as $key => $val) {
            if (substr($key,0,6) == 'member') {
                array_push($memberArr, $val);
            }
        }
        $memberHandle = 1;
        foreach($memberArr as $key => $val) {
            if ($memberHandle == 1) {
                $memberName = $val;
                $memberHandle++;
            }else if ($memberHandle == 2) {
                $memberDetail = $val;   
                $memberHandle++;
            }else if ($memberHandle == 3) {
                $memberPrice = $val;
                $memberHandle = 1;
                $conditionMember = array(
                    'name' => $memberName,
                    'price' => $memberPrice,
                    'spec' => $memberDetail
                );
                //添加member，获取ID
                array_push($memberId, $HotelMember -> add($conditionMember));
            }
        }

        //把member id 添加至home style下
        $result = $HotelHomeStyle -> where(array('id' => $homeStyleId)) -> select();
        $result = $result[0];
        $newIdStr = implode('、', $memberId);
        $newIdStr = $result['member_id'].'、'.$newIdStr;
        if ($HotelHomeStyle -> where(array('id' => $homeStyleId)) -> save(array('member_id' => $newIdStr))) {
            $params = array(
                'status' => 'success',
                'info' => '会员类别添加成功!',
                'pageName' => 'hotel',
                'isChild' => 'true',
                'childName' => 'view_hotel',
                'pageId' => $hotelId
            );
            $this -> redirect('Admin/toHotel', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '会员类别添加失败!',
                'pageName' => 'hotel',
                'isChild' => 'true',
                'childName' => 'view_hotel',
                'pageId' => $hotelId
            );
            $this -> redirect('Admin/toHotel', $params);
        }
    }

    // 根据home_style_id 查询homestyle
    public function hotelSelectHomeStyleById($id) {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_home_style');

        $result = $Dao -> where(array('id' => $id)) -> order('id desc') -> select();
        return $result;
    }
    public function hotelSelectHomeStyleByIdAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_home_style');
        $id = $_POST['id'];

        $result = $Dao -> where(array('id' => $id)) -> order('id desc') -> select();
        $this -> ajaxReturn($result);
    }

    // 根据member_id 查询member
    public function hotelSelectMemberById($id) {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_member');

        $result = $Dao -> where(array('id' => $id)) -> order('id desc') -> select();
        return $result;
    }
    public function hotelSelectMemberByIdAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Hotel_member');
        $id = $_POST['id'];
        $homeStyleId = $_POST['theme_id'];

        $result = $Dao -> where(array('id' => $id)) -> order('id desc') -> select();
        $result[0]['theme_id'] = $homeStyleId;
        $this -> ajaxReturn($result);
    }


    // 账号 增、删
    public function accountAdd() {
    	header("Content-Type:text/html; charset=utf-8");
    	// var_dump($_POST);
    	$User = D('Manager');
        
        $_POST['phone'] = $_POST['account'];
        // var_dump($_POST);
        // die();
    	$result = $User -> where(array('phone' => $_POST['account'])) -> select();
    	if (!$result) {
    		if ($User -> data($_POST) -> add()) {
    			$params = array(
    				'status' => 'success', 
    				'info' => '账号添加成功!',
    				'pageName' => 'accountManage'
    			);
	    		$this -> redirect('Admin/toAccountManage', $params);
	    	}else {
	    		$params = array(
    				'status' => 'fail',
    				'info' => '账号添加失败!',
    				'pageName' => 'accountManage'
    			);
    			$this -> redirect('Admin/toAccountManage', $params);
	    	}	
    	}else {
    		$params = array(
				'status' => 'fail',
				'info' => '账号已存在!',
				'pageName' => 'accountManage'
			);
			$this -> redirect('Admin/toAccountManage', $params);
    	}

    }

    public function accountDelete() {
        header("Content-Type:text/html; charset=utf-8");
    	// var_dump($_GET);
    	$User = D('Manager');
    	$condition = array(
    		'id' => $_GET['deleteId']
    	);
    	if ($User -> where($condition) -> delete()) {
    		$params = array(
				'status' => 'success',
				'info' => '账号删除成功!',
				'pageName' => 'accountManage'
			);
    		$this -> redirect('Admin/toAccountManage', $params);
    	}else {
    		$params = array(
				'status' => 'success',
				'info' => '账号删除失败!',
				'pageName' => 'accountManage'
			);
			$this -> redirect('Admin/toAccountManage', $params);
    	}

    }

    public function accountUpdate() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('User');
        $id = $_GET['id'];
        $year = $_POST['birthday_year'];
        $month = $_POST['birthday_month'];
        $day = $_POST['birthday_day'];
        $birthday = $year."/".$month."/".$day;
        $_POST['birthday'] = $birthday;
        $imageUrl = '';

        $result = $Dao -> where(array('id' => $id)) -> select();
        $result = $result[0];
        $image = $result['image'];
        $image = '.'.$image;

        // 图片上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     314572800;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/photos/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            // $params = array(
            //     'status'=> 'fail',
            //     'info'=> '用户信息更改失败!',
            //     'pageName' => 'center',
            //     'isChild' => 'true',
            //     'childName' => 'personal_info'
            // );
            // $this -> redirect('Admin/toCenter', $params);
        }else{// 上传成功 获取上传文件信息
            foreach($info as $file){
                // echo $file['savepath'].$file['savename'];
                $imageUrl = '/Upload/photos/'.$file['savepath'].$file['savename'];
            }
        }

        if ($imageUrl != '') {
            $_POST['image'] = $imageUrl;
            unlink($image);
        }

        // var_dump($_POST);
        // var_dump($image);
        // var_dump('---------------------------------------------');
        // var_dump($imageUrl);
        // die();
        
        if ($Dao -> where(array('id' => $id)) -> save($_POST)) {
            $params = array(
                'status'=> 'success',
                'info'=> '用户信息更改成功!',
                'pageName' => 'center',
                'isChild' => 'true',
                'childName' => 'personal_info'
            );
            $this -> redirect('Index/toCenter', $params);
        }else {
            $params = array(
                'status'=> 'fail',
                'info'=> '用户信息更改失败!',
                'pageName' => 'center',
                'isChild' => 'true',
                'childName' => 'personal_info'
            );
            $this -> redirect('Index/toCenter', $params);
        }
    }

    //订单删除
    public function orderDelete() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Order');
        $id = $_GET['deleteId'];        
        if ($Dao -> where(array('id' => $id)) -> delete()) {
            $params = array(
                'status' => 'success',
                'info' => '订单删除成功!',
                'pageName' => 'message'
            );
            $this -> redirect('Admin/toMessage', $params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '订单删除失败!',
                'pageName' => 'message'
            );
            $this -> redirect('Admin/toMessage', $params);
        }
    }

    //viewCount
    public function orderViewCount() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Order');
        $result = $Dao -> where(array('view' => '0')) -> select();
        return count($result);
    }

    public function orderSelectByStatus($status) {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Order');
        $condition = array();
        if ($status == '-1') {
            
        }else {
            $condition = array(
                'status' => $status
            );
        }

        $result = $Dao -> where($condition) -> order('id desc') -> select();
        if ($result) {
            // $param = array(
            //     'code'=> '200',
            //     'status'=> 'success',
            //     'data'=> $result
            // );
            return $result;
        }else {
            // $param = array(
            //     'code'=> '400',
            //     'status'=> 'fail'
            // );
            return $result;
        }
    }

    public function orderSelectByStatusAjaxReturn() {
        header('content-type:text/html;charset=utf-8');
        $Dao = D('Order');
        $condition = array();

        if ($_POST['status'] == '-1') {
            
        }else {
            $condition = array(
                'status' => $_POST['status']
            );
        }

        $result = $Dao -> where($condition) -> order('id desc') -> select();
        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data'=> $result
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'code'=> '400',
                'status'=> 'fail'
            );
            $this -> ajaxReturn($param);
        }
    }

    //订单view更改
    public function orderViewChangeAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Order');
        $id = $_POST['id'];
        $content = array('view' => 1);
        if ($Dao -> where(array('id' => $id)) -> save($content)) {
            $params = array(
                'status' => 'success',
                'info' => 'view修改成功!',
                'pageName' => 'message'
            );
            $this -> ajaxReturn($params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => 'view修改失败!',
                'pageName' => 'message'
            );
            $this -> ajaxReturn($params);
        }
    }

    //订单状态修改
    public function orderStatusUpdate() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('Order');
        $User = D('User');

        $id = $_GET['deleteId'];
        $status = $_GET['status'];

        if ($Dao -> where(array('id' => $id)) -> save(array('status' => $status))) { //修改订单状态
            if ($status == '3') {
                //更新score--------------------------
                //获取user phone
                $Order = $Dao -> where(array('id' => $id)) -> select();
                $Order = $Order[0];
                $oldScore = floatval($Order['used_score']);
                $userPhone = $Order['user_phone'];                

                $result  = $User -> where(array('phone' => $userPhone)) -> select();
                $result = $result[0];
                $newScore = floatval($Order['now_score']) + $oldScore - $Order['price'];
                $User -> where(array('phone' => $userPhone)) -> save(array('score' => $newScore));
            }

            $params = array(
                'status' => 'success',
                'info' => '确认成功!',
                'pageName' => 'message'
            );
            $this -> redirect('Admin/toMessage',$params);
        }else {
            $params = array(
                'status' => 'fail',
                'info' => '确认失败!',
                'pageName' => 'message'
            );
            $this -> redirect('Admin/toMessage',$params);
        }
    }

    
    // Common function ------------------------------------------------
    public function selectAll($tableName) {
    	header("Content-Type:text/html; charset=utf-8");
    	$Dao = D($tableName);
    	$result = $Dao -> order('id DESC') -> select();
    	return $result;
    }

    public function selectAllByAttr($tableName,$arr) {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D($tableName);
        $result = $Dao -> where($arr) -> order('id DESC') -> select();
        return $result;   
    }

    public function selectCount($tableName) {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D($tableName);
        $result = $Dao -> count();
        return $result;
    }

    // 从第 $index 开始，拿 $count 个数据
    public function selectGetCount($tableName,$index,$count) {
        header("Content-Type:text/html; charset=utf-8");
        $count = $count+$index;
        $Dao = D($tableName);
        $result = $Dao -> order('id DESC') -> select();
        for($i=$index; $i<$count; $i++) {
            if (!empty($result[$i])) {
                $newResult[$i] = $result[$i];
            }
        }
        return $newResult;
    }

    public function selectGetCountAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $tableName = $_POST['tableName'];
        $index = $_POST['index'];
        $count = $_POST['count'];

        $count = $count+$index;
        $Dao = D($tableName);
        $result = $Dao -> order('id DESC') -> select();
        $newResult = array();
        for($i=$index; $i<$count; $i++) {
            if (!empty($result[$i])) {
                $newResult[$i] = $result[$i];
            }
        }
        $this -> ajaxReturn($newResult);
    }

    public function selectAllAjaxReturn() {
        header("Content-Type:text/html; charset=utf-8");
        $tableName = $_POST['tableName'];
        $Dao = D($tableName);
        $result = $Dao -> order('id DESC') -> select();
        $this -> ajaxReturn($result);
    }

    public function deleteOne($tableName, $id){
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D($tableName);
        if ($Dao -> where(array('id' => $id)) -> delete()) {
            return 'success';
        }else {
            return 'fail';
        }
    }

    public function selectOne($tableName, $id) {
    	header("Content-Type:text/html; charset=utf-8");
    	$Dao = D($tableName);
    	$result = $Dao -> where(array('id' => $id)) -> order('id DESC') -> select();
    	return $result;	
    }

    public function loginConfirm() {
    	$loginStatus = session('loginStatus');
    	if ($loginStatus ==  'true') {
    		return true;
    	}else {
    		return false;
    	}
    }

    public function login() {
    	header("Content-Type:text/html; charset=utf-8");
    	// var_dump($_POST);
    	$User = D('Manager');
    	$result = $User -> where($_POST) -> select();
    	// var_dump($result);
    	if ($result) {
            if ($result[0]['level'] > 5) {
                session('loginStatus', 'true');
                session('account', $result[0]['phone']);
                session('name', $result[0]['name']);
                session('level', $result[0]['level']);
                // $this -> display('Admin/Index/index');
                $this -> ajaxReturn(array('msg' => 'success'));
            }else {
                $this -> ajaxReturn(array(
                    'msg' => 'fail',
                    'info' => '账号密码有误'
                ));
            }
    	}else {
    		$this -> ajaxReturn(array(
    			'msg' => 'fail',
    			'info' => '账号密码有误'
    		));
    	}
    }

    public function logout() {
    	session('loginStatus', null);
    	session('account', null);
    	session('name', null);
    	if ($this -> loginConfirm()) {
    		$this -> display("Admin/Index/index");
    	}else {
    		$this -> display("Admin/Login/index");
    	}
    }

    public function sendPhoneMessage() {
        $sendModel = $_POST['send_model'];      //发送模板
        $randomNum = '';

        if ($sendModel == 'sign_up') {
            //验证码
            $randomNum = rand(100000,999999);
            
            $userPhone = $_POST['phone'];           //发送给某个手机号
            $content = urlencode(iconv("UTF-8","GB2312","【嘉优隆精品酒店】您好，本次操作的验证码为".$randomNum."。"));
        }else if($sendModel == 'order_remind') {
            //订单提醒
            $userPhone = '18164626080';
            $content = urlencode(iconv("UTF-8","GB2312","【嘉优隆精品酒店】您好，有一条新订单，请上后台操作。"));
        }

        $url="http://service.winic.org/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=";
        $id = urlencode("jiayoulong");
        $pwd = urlencode("jiayoulong123");
        $to = urlencode($userPhone);
        $rurl = sprintf($url, $id, $pwd, $to, $content);
        // printf("url=%s\n", $rurl);
        $ret = file($rurl);
        // print_r($ret);

        $param = array(
            'randomNum' => $randomNum
        );
        $this -> ajaxReturn($param);
    }

    public function keyMD5() {
        $sign = $_GET['sign'];
        $this -> ajaxReturn(md5($sign));
    }
}