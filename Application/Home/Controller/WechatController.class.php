<?php
namespace Home\Controller;
use Think\Controller;
class WechatController extends Controller {
    //生成二维码
    public function createTDC() {
        header('content-type:text/html;charset=utf-8');
        //获取token
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
            'grant_type' => 'client_credential',
            'appid' => 'wx4796405728efcfcd',
            'secret' => '310b7b99eb9bc4fe6a2f621704b70ecc'
        );
        $result = $this -> http($url, $data, 'GET', array("Content-type: text/html; charset=utf-8"));
        $token = json_decode($result);
        $token = $token->access_token;

        //获取二维码
        $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$token;
        $data1 = array(
            'path' => 'pages/index/index',
            'width' => '430',
            'auto_color' => false
        );
        $result = $this -> http($url, $data1, 'POST', array("Content-type: text/html; charset=utf-8"));
        $this -> ajaxReturn($result);
    }

    //用户添加(注册)
    public function userAdd(){
        header('content-type:text/html;charset=utf-8');
        $Dao = D('User');
        
        $phoneNumber = $_POST['phone'];
        $openId = $_POST['open_id'];

        $result = $Dao -> where(array('phone' => $phoneNumber)) -> select();
        $resultOpenId = $Dao -> where(array('open_id' => $openId)) -> select();
        //电话和open_id不存在 continue
        if (!$result && !$resultOpenId) {
            $condition = array(
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'open_id' => $_POST['open_id'],
                'level' => '1',
                'score' => '0'
            );
            if ($Dao -> data($condition) -> add()) {
                $param = array(
                    'code'=> '200',
                    'status'=> 'success',
                    'data' => $condition
                );
                $this -> ajaxReturn($param);
            }else {
                $param = array(
                    'code'=> '400',
                    'status'=> 'fail',
                    'info' => '注册失败'
                );
                $this -> ajaxReturn($param);
            }
        }else {
            $param = array(
                'code'=> '400',
                'status'=> 'fail',
                'info' => '该号码已存在'
            );
            $this -> ajaxReturn($param);
        }
    }

    //用户手机号更换
    public function userPhoneUpdate() {
        header("Content-Type:text/html; charset=utf-8");
        $Dao = D('User');
        $oldPhone = $_POST['old_phone'];
        $newPhone = $_POST['new_phone'];

        $result = $Dao -> where(array('phone' => $oldPhone)) -> select();
        if ($result) {
            if ($Dao -> where(array('id' => $result[0]['id'])) -> save(array('phone' => $newPhone))) {
                $param = array(
                    'code'=> '200',
                    'status'=> 'success'
                );
                $this -> ajaxReturn($param);
            }else {
                $param = array(
                    'code'=> '400',
                    'status'=> 'fail'
                );
                $this -> ajaxReturn($param);
            }
        }else {
            $param = array(
                'code'=> '400',
                'status'=> 'fail',
                'info' => '老手机号不存在'
            );
            $this -> ajaxReturn($param);
        }
    }

    //全部用户查询
    public function userSelect(){
        header('content-type:text/html;charset=utf-8');
        $Dao = D('User');
        if ($result = $Dao -> select()) {
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

    // car sale offer ------------------------------------------------------------------
    public function carSaleOfferSelectByBestPrice() {
        header('content-type:text/html;charset=utf-8');
        $CarSaleOffer = D('Car_sale_offer');

        //数据接收
        $car_sale_id = $_POST['car_sale_id'];

        $result = $CarSaleOffer -> where(array('car_sale_id' => $car_sale_id)) -> order('id desc') -> select();

        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data' => $result[0]
            );
            $this -> ajaxReturn($param);
        }else {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data' => ''
            );
            $this -> ajaxReturn($param);
        }
    }

    public function carSaleOfferAdd() {
        header('content-type:text/html;charset=utf-8');
        $CarSaleOffer = D('Car_sale_offer');

        //时间戳转日期
        $publish_time = date("Y-m-d H:i:s",time());
        $_POST['publish_time'] = $publish_time;

        if ($CarSaleOffer -> data($_POST) -> add()) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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
    // car sale offer ------------------------------------------------------------------ end

    // car sale 车辆拍卖---------------------------------------------------------------------------------------
    public function carSalePendingById() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $CarSaleOffer = D('Car_sale_offer');
        $Dao = D('Car_sale');
        $newArr = array();

        $id = $_POST['id'];

        $result = $Dao -> where(array('car_id' => $id)) -> order('id desc') -> select();
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

            //数据接收
            $car_sale_id = $val1['id'];
            $carOffer = $CarSaleOffer -> where(array('car_sale_id' => $car_sale_id)) -> order('id desc') -> select();
            if ($carOffer) {
                $singleCar['best_price'] = $carOffer[0]['offer_price'];
            }else {
                $singleCar['best_price'] = '';
            }

            array_push($newArr, $singleCar);
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $newArr
        );

        $this -> ajaxReturn($param);
    }

    public function carSalePendingByStatus() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $CarSaleOffer = D('Car_sale_offer');
        $Dao = D('Car_sale');
        $newArr = array();

        $status = $_POST['status'];

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

            //数据接收
            $car_sale_id = $val1['id'];
            $carOffer = $CarSaleOffer -> where(array('car_sale_id' => $car_sale_id)) -> order('id desc') -> select();
            if ($carOffer) {
                $singleCar['best_price'] = $carOffer[0]['offer_price'];
            }else {
                $singleCar['best_price'] = '';
            }

            array_push($newArr, $singleCar);
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $newArr
        );

        $this -> ajaxReturn($param);
    }
    // car sale 车辆拍卖--------------------------------------------------------------------------------------- end

    // 新车、二手车--------------------------------------------------------------------------------------------
    //车辆查询 (类别、城市、品牌)
    public function carSelectByCityAndBrand() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('CarBrand');
        $CarStyle = D('CarStyle');

        $category = $_POST['category'];
        $city_name = $_POST['city_name'];
        $brand_name = $_POST['brand_name'];

        // $category = '1';
        // $city_name = '曲靖市';
        // $brand_name = '宝马';

        //get brand id
        $result = $CarBrand -> where(array('name' => $brand_name)) -> select();
        $brand_id = $result[0]['id'];

        $conditionCar = array(
            'city_name' => $city_name,
            'category' => $category,
            'brand_id' => $brand_id
        );
        $result = $Car -> where($conditionCar) -> select();
        foreach($result as $key => $val) {
            $result[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $result[$key]['style_name'] = $style_name;
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $result
        );

        $this -> ajaxReturn($param);
    }

    public function carSelectByPrice() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('CarBrand');
        $CarStyle = D('CarStyle');

        $category = $_POST['category'];
        $city_name = $_POST['city_name'];
        $price_a = $_POST['price_a'];
        $price_b = $_POST['price_b'];

        // $category = '1';
        // $city_name = '昆明市';
        // $price_a = '10';
        // $price_b = '60';


        $result = $Car -> where('price>='.$price_a.' AND price<='.$price_b) -> where(array('city_name' => $city_name, 'category' => $category)) -> select();
        foreach($result as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            $brand_name = $brand_name[0]['name'];
            $result[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $result[$key]['style_name'] = $style_name;
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $result
        );

        $this -> ajaxReturn($param);
        // var_dump($result);
    }

    // 价格最高/价格最低 status: up/down
    public function carSelectByPriceVarious() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('CarBrand');
        $CarStyle = D('CarStyle');

        $brand_name = $_POST['brand_name'];

        // $category = '1';
        // $city_name = '昆明市';
        // $price_a = '0';
        // $price_b = '100000';
        // $status = 'price_down';

        $category = $_POST['category'];
        $city_name = $_POST['city_name'];
        $price_a = $_POST['price_a'];
        $price_b = $_POST['price_b'];

        //价格最低、最高、里程最少、车年最短、最新发布
        $status = $_POST['status'];
        // $status = 'publish_time_up';

        $orderStr = '';
        if ($status == 'price_up') {                //价格最高
            $orderStr = 'price desc';           
        }else if ($status == 'price_down') {        //价格最低
            $orderStr = 'price asc';            
        }else if ($status == 'mileage_down') {      //里程最少
            $orderStr = 'mileage asc';          
        }else if ($status = 'buy_time_up') {        //车年最短
            $orderStr = 'buy_time desc';        
        }else if ($status = 'publish_time_up') {    //最新发布
            $orderStr = 'id desc';              
        }

        $conditionArr = array(
            'city_name' => $city_name,
            'category' => $category
        );

        //是否有brand_name
        if (!empty($brand_name)) {
            $singleCar = $CarBrand -> where(array('name' => $brand_name)) -> select();
            $conditionArr['brand_id'] = $singleCar[0]['id'];
        }

        $result = $Car -> where('price>='.$price_a.' AND price<='.$price_b)
                        -> where($conditionArr)
                        -> order($orderStr)
                        -> select();

        foreach($result as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            $brand_name = $brand_name[0]['name'];
            $result[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $result[$key]['style_name'] = $style_name;
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $result
        );

        $this -> ajaxReturn($param);
        // var_dump($result);
    }

    // 单一车辆查询
    public function carSelectById() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('CarBrand');
        $CarStyle = D('CarStyle');

        $car_id = $_POST['car_id'];

        $result = $Car -> where(array('id' => $car_id)) -> select();
        foreach($result as $key => $val) {
            $brand_name = $CarBrand -> where(array('id' => $val['brand_id'])) -> select();
            $brand_name = $brand_name[0]['name'];
            $result[$key]['brand_name'] = $brand_name;

            $style_name = $CarStyle -> where(array('id' => $val['style_id'])) -> select();
            $style_name = $style_name[0]['name'];
            $result[$key]['style_name'] = $style_name;
        }

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $result
        );

        $this -> ajaxReturn($param);
        // var_dump($param);
    }

    // 所有品牌搜索
    public function carBrandSelectAll() {
        header('content-type:text/html;charset=utf-8');
        $CarBrand = D('CarBrand');

        $result = $CarBrand -> order('initial') -> select();
        $this -> ajaxReturn($result);
    }

    // 足迹------------------------------------------
    public function footprintAdd() {
        header('content-type:text/html;charset=utf-8');
        $Footprint = D('Footprint');

        $user_phone = $_POST['user_phone'];
        $car_id = $_POST['car_id'];

        $result = $Footprint -> where(array('user_phone' => $user_phone)) -> select();

        if ($result) {
            $result = $result[0];
            $car_id_arr = explode(' | ', $result['car_id_arr']);
            array_push($car_id_arr, $car_id);
        }else {
            $Footprint -> data(array('user_phone' => $user_phone, 'car_id_arr' => $car_id)) -> add();
            $result = $Footprint -> where(array('user_phone' => $user_phone)) -> select();
            $result = $result[0];
            $car_id_arr = explode(' | ', $result['car_id_arr']);
        }

        //最多20个足迹
        if (count($car_id_arr) > 20) {
            unset($car_id_arr[0]);
        }
        $car_id_arr = implode(' | ', $car_id_arr);

        if ($Footprint -> where(array('user_phone' => $user_phone)) -> save(array('car_id_arr' => $car_id_arr))) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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

    public function footprintDeleteAll() {
        header('content-type:text/html;charset=utf-8');
        $Footprint = D('Footprint');

        $user_phone = $_POST['user_phone'];

        if ($Footprint -> where(array('user_phone' => $user_phone)) -> delete()) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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

    public function footprintSelectByPhone() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $Footprint = D('Footprint');

        $user_phone = $_POST['user_phone'];
        // $user_phone = '18164626080';

        $result = $Footprint -> where(array('user_phone' => $user_phone)) -> select();
        $result = $result[0];

        $car_id_arr = $result['car_id_arr'];
        $car_id_arr = explode(' | ', $car_id_arr);

        $newArr = array();
        foreach ($car_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            if ($simpleCar) {
                $simpleCar = $simpleCar[0];
                array_unshift($newArr, $simpleCar);
            }
        }

        foreach($newArr as $key => $val) {
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

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'data' => $newArr
        );
        $this -> ajaxReturn($param);
    }

    // 足迹------------------------------------------ end

    // 收藏------------------------------------------
    public function collectionAdd() {
        header('content-type:text/html;charset=utf-8');
        $Collection = D('Collection');

        $user_phone = $_POST['user_phone'];
        $car_id = $_POST['car_id'];

        $result = $Collection -> where(array('user_phone' => $user_phone)) -> select();

        if ($result) {
            $result = $result[0];
            if ($result['car_id_arr'] ==  '') {
                $car_id_arr = array();
                array_push($car_id_arr, $car_id);
            }else {
                $car_id_arr = explode(' | ', $result['car_id_arr']);
                array_push($car_id_arr, $car_id);
            }
        }else {
            $Collection -> data(array('user_phone' => $user_phone, 'car_id_arr' => $car_id)) -> add();
            $result = $Collection -> where(array('user_phone' => $user_phone)) -> select();
            $result = $result[0];

            $car_id_arr = array();
            array_push($car_id_arr, $car_id);
        }

        $car_id_arr = implode(' | ', $car_id_arr);

        if ($Collection -> where(array('user_phone' => $user_phone)) -> save(array('car_id_arr' => $car_id_arr))) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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

    public function collectionDelete() {
        header('content-type:text/html;charset=utf-8');
        $Collection = D('Collection');

        $user_phone = $_POST['user_phone'];
        $car_id = $_POST['car_id'];

        $result = $Collection -> where(array('user_phone' => $user_phone)) -> select();

        $result = $result[0];
        $car_id_arr = explode(' | ', $result['car_id_arr']);
        foreach ($car_id_arr as $key => $val) {
            if ($val == $car_id) {
                unset($car_id_arr[$key]);
            }
        }
        
        $car_id_arr = implode(' | ', $car_id_arr);

        if ($Collection -> where(array('user_phone' => $user_phone)) -> save(array('car_id_arr' => $car_id_arr))) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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

    public function collectionSelectByPhone() {
        header('content-type:text/html;charset=utf-8');
        $Car = D('Car');
        $CarBrand = D('Car_brand');
        $CarStyle = D('Car_style');
        $Collection = D('Collection');

        $user_phone = $_POST['user_phone'];
        // $user_phone = '18164626080';

        $result = $Collection -> where(array('user_phone' => $user_phone)) -> select();
        $result = $result[0];

        $car_id_arr = $result['car_id_arr'];

        $car_id_arr = explode(' | ', $car_id_arr);
        

        $newArr = array();
        foreach ($car_id_arr as $key => $val) {
            $simpleCar = $Car -> where(array('id' => $val)) -> select();
            if ($simpleCar) {
                $simpleCar = $simpleCar[0];
                array_unshift($newArr, $simpleCar);
            }
        }

        foreach($newArr as $key => $val) {
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

        $param = array(
            'code'=> '200',
            'status'=> 'success',
            'car_id_arr' => $car_id_arr,
            'data' => $newArr
        );
        $this -> ajaxReturn($param);
    }

    // 收藏------------------------------------------ end


    // 热门------------------------------------------
    public function carHotSelectByCategory() {
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
    // 热门------------------------------------------ end

    // 待发布车辆 PendingVehicle -------------------------------------------------------------
    public function pendingVehicleAdd() {
        header('content-type:text/html;charset=utf-8');
        $PendingVehicle = D('Pending_vehicle');

        $publish_time = date('Y-m-d');
        $_POST['publish_time'] = $publish_time;
        $_POST['visit_qty'] = 0;
        $_POST['status'] = 0;

        if ($PendingVehicle -> data($_POST) -> add()) {
            $param = array(
                'code'=> '200',
                'status'=> 'success'
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

    public function pendingVehicleSelectByPhone() {
        header('content-type:text/html;charset=utf-8');
        $PendingVehicle = D('Pending_vehicle');

        //数据接收
        $user_phone = $_POST['user_phone'];

        //数据查询
        $result = $PendingVehicle -> where(array('user_phone' => $user_phone)) -> order('id desc') -> select();
        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data' => $result
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
    // 待发布车辆 PendingVehicle ------------------------------------------------------------- end





















































   



















































    //腾讯地图 地址转经纬度
    public function mapAddressTranslation() {
        header('content-type:text/html;charset=utf-8');
        $address = $_GET['address'];
        $url = 'http://api.map.baidu.com/geocoder/v2/';
        $data = array(
            'address' => $address,
            'ak' => '9GdMiuswChEEoGiPFCsi68QGZXQiH1aH',
            'output' => 'json'
        );
        $result = $this -> http($url, $data, 'POST', array("Content-type: text/html; charset=utf-8"));
        $this -> ajaxReturn($result);
    }

    //小程序用户登录
    public function userLogin() {
        header('content-type:text/html;charset=utf-8');
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $appId = 'wx4796405728efcfcd';
        $secret = '310b7b99eb9bc4fe6a2f621704b70ecc';
        $jsCode = $_GET['code'];
        $grantType = 'authorization_code';

        $data = array(
            'appid' => $appId,
            'secret' => $secret,
            'js_code' => $jsCode,
            'grant_type' => $grantType
        );

        $result = $this -> http($url, $data, 'GET', array("Content-type: text/html; charset=utf-8"));
        $this -> ajaxReturn($result);
    }

    //小程序登录状态验证
    public function userLoginStatusConfirm() {
        header('content-type:text/html;charset=utf-8');
        $Dao = D('User');
        $openId = $_POST['open_id'];

        $result = $Dao -> where(array('open_id' => $openId)) -> select();
        if ($result) {
            $param = array(
                'code'=> '200',
                'status'=> 'success',
                'data'=> $result[0]
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

    //省份查询 腾讯API
    public function txProvinceSelect() {
        header('content-type:text/html;charset=utf-8');
        $url = 'http://apis.map.qq.com/ws/district/v1/list';
        $method = 'GET';
        $data = array(
            'key' => 'MRABZ-LIH3U-Q5FVO-4N7XT-DPIUQ-OFBZO',
            'output' => 'json'
        );

        $result = $this -> http($url, $data, $method, array("Content-type: text/html; charset=utf-8"));
        $this -> ajaxReturn($result);
    }

    //城市区域查询 腾讯API
    public function txCityAreaSelect() {
        header('content-type:text/html;charset=utf-8');
        $id = $_POST['id'];
        $url = 'http://apis.map.qq.com/ws/district/v1/getchildren';
        $method = 'GET';
        $data = array(
            'key' => 'MRABZ-LIH3U-Q5FVO-4N7XT-DPIUQ-OFBZO',
            'id' => $id,
            'output' => 'json'
        );

        $result = $this -> http($url, $data, $method, array("Content-type: text/html; charset=utf-8"));
        $this -> ajaxReturn($result);
    }

    //http请求
    function http($url, $params, $method = 'GET', $header = array(), $multi = false){
        $opts = array(
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;
    }
}