#DBMZ-WEB

##User

####判断用户是否登录

	request	:	GET		/user/isLogin
	
	params	:	{}
	
	success	:	{code : 200 , msg : 已登录, data : [0 => User(用户对象)]}
	
	error	:	{code : 404 , msg : 未登录}
	
####用户注册

	request	:	POST	/user/register
	
	params	:	{phoneNumber : 手机号码, password : 密码, code : 验证码}
	
	success	:	{code : 200, msg : 注册成功}
	
	error	:	{code : 405, msg : [验证码不正确、手机号码不正确、手机号码已经存在]}
	            {code : 403, msg : 未传参数}
	            
####发送验证码

    request : GET   /user/sendVerificationCode
    
    params  : {phoneNumber : 手机号码}
    
    success : {code : 200, data : [], msg : 短信发送成功}
    
    error : {code : 402, data : [], msg : 发生错误}
            {code : 403, data : [], msg : 未传参数}
            {code : 404, data : [], msg : 验证码已经发送过了}
	
####用户登录

	request	:	POST	/user/login
	
	params	:	{phoneNumber : 手机号码, password : 密码}
	
	success	:	{code : 200, msg : 登录成功, data : [0 => User(用户对象)]}
	
	error	:	{code : 405 , msg : 用户名或密码错误}
	
####用户登出

	request	:	GET		/user/logout
	
	params	:	{}
	
	success	:	{code : 200 , msg : 退出成功}
	
	error	: {}
	
####获取某个用户的信息(手机端)

    request : GET   /user/getUserMobile
    
    params : {uid:用户ID}
    
    success : {code : 200, data: [user], msg: 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}
            {code : 404, data : [], msg : 未找到此用户}
            
####修改昵称

    request : POST   /user/updateNickname
    
    params : {nickname}
    
    success : {"code":200,"data":[],"msg":"昵称修改成功"}
    
    error : {"code":403,"data":[],"msg":"参数错误"}

####修改密码

    request : POST   /user/updatePassword
    
    params : {oldpwd:旧密码，newpwd:新密码}
    
    success : {"code":200,"data":[],"msg":"密码修改成功"}
    
    error : {"code":403,"data":[],"msg":"参数错误"}
            {"code":404,"data":[],"msg":"旧密码不正确"}
            
####获取某个用户的红包记录

    request : GET   /user/getHbRecords
    
    params : {}
    
    success : {"code":200,"data":[{"userName","avatorUrl","id","time","amount","type"},{"userName","avatorUrl","id","time","amount","type"}],"msg":"获取红包记录成功"}
	
####根据邀请码获取邀请者的头像和昵称

    request : GET   /user/getUserByInviteCode
    
    params : {inviteCode}
    
    success : {"code":200,"data":{"userName","avatorUrl"},"msg":"获取成功"}
                {"code":404,"data":[],"msg":"无此用户"}
                
####意见反馈

    request : POST   /user/suggest
    
    params : {theme,email,content}
    
    success : {"code":200,"data":[],"msg":"提交建议成功"}
    
    error : {"code":403,"data":[],"msg":"参数错误"}
            {"code":401,"data":[],"msg":"提交建议失败"}
            
####修改showHb字段为0

    request : GET   /user/notShowHb
    
    params : {}
    
    success : {"code":200,"data":[],"msg":"修改成功"}
    
    error :  {"code":401,"data":[],"msg":"修改失败"}
    
    
##Category

####获得某个分类

    request :   GET     /category/getAll
    
    params  :   {}
    
    success :   {code : 200 , msg : 获取成功, data : []}
    
    error   :   {}

####获得某个分类

	request	:	GET		/category/getById
	
	params	:	{id : 分类id}
	
	success	:	{code : 200 , msg : 获取成功, data : Category(分类对象)/可能null}
	
	error	:	{}

##Yungou

####获取所有进行中云购和对应商品，默认按照云购创建时间排序

	request	:	GET	/yungou/getAll
	
	params	:	{search: 搜索关键字(选填)}
	
	success	:	{code : 200 , data : [{product:product, yungou: yungou}], msg : 获取成功}
	
	error	:	{}

####获取指定分类进行中的云购和对应商品，默认按照云购创建时间排序

	request	:	GET	/yungou/getCategory
	
	params	:	{id : categoryId}
	
	success	:	{code : 200 , data : [{product:product, yungou: yungou}], msg : 获取成功}
	
	error	:	{}
	
####获取最新揭晓的云购和对应商品
    
    request : GET /yungou/fastStart
    
    params :  {startTime : 最后一条记录的时间(选填), limit : 数量}
    
    success : {code : 200 , data : [{productId,term,title,thumbnailUrl,price,saleCount,startTime,endTime,timeout,orderId(没有则为null),result(没有则为null),status},{}....] , msg : 获取成功}
    
    error : {}
	
####获取往期云购
    
    request : GET /yungou/getHistory
    
    params : {productId : 商品id, limit: 一次取多少条, startTime: 最后一条记录的时间(选填)}
    
    success : {code : 200, data : [{term,result,endTime,orderId,status,yungouId,startTime,avatorUrl,userName,userId,buyTime,loginArea,count,timeout},{}...], msg :获取成功}
    
    error : {code :403, data : [], msg : 未传参数}

####获取往期云购(手机端)

    request : GET /yungou/getHistory
    
    params : {productId : 商品id, limit: 一次取多少条, startTime: 最后一条记录的时间(选填)}
    
    success : {code : 200, data : [{term,result,endTime,orderId,status,yungouId,startTime,avatorUrl,userName,userId,buyTime,loginArea,count},{}...], msg :获取成功}
    
    error : {code :403, data : [], msg : 未传参数}


####获取往期云购总数

    request : GET /yungou/getHistoryCount
    
    params : {productId : 商品id}
    
    success : {code : 200, data: count, msg : 获取成功}
    
    error: {code :403, data: [], msg : 未传参数}
    
####获取某个云购的开奖后的结果

    request : GET /yungou/getWin
    
    params : {id: 云购id}
    
    success: {code : 200, data:  [{term,result,endTime,orderId,status,yungouId,startTime,avatorUrl,userName,ip,userId,buyTime,loginArea,count,timeout}], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 405, data: [], msg : 数据为空}
            
####获取云购的计算结果

    request : GET /yungou/compute
    
    params : {id : 云购id}
    
    success : {code : 200, data : [{buyTime,userName,userId,title,term,productId,count},{}...], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####获取云购的A、B、中奖号码、时时彩值

    request : GET /yungou/getResult
    
    params : {id : 云购id}
    
    success : {code : 200, data : [{A,B,result,sscTerm}], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####根据productId和term找出云购和商品的信息

    request : GET   /yungou/getByProductAndTerm
    
    params : {pid : 商品ID, term : 期数}
    
    success : {code : 200, data : {yungou:云购对象,product:商品对象}, msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 404, data: [], msg : 查找不到数据}
            
####根据yungouId找出云购和商品的信息

    request : GET   /yungou/getByYungou
    
    params : {yid : 云购ID}
    
    success : {code : 200, data : {yungou:云购对象,product:商品对象}, msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 404, data: [], msg : 查找不到数据}
            
####根据yungouId找出云购和商品和幸运号码，用于展示在用户晒单页面

    request : GET   /yungou/getYgForShow
    
    params : {yid : 云购ID}
    
    success : {"code":200,"data":{"title","orderId","term","result","parCount","endTime"},"msg":"获取成功"}
    
    error : {"code":404,"data":[],"msg":"没有查询到结果"}
            {"code":403,"data":[],"msg":"未传参数"}
            
####找出十元专区的云购和商品信息

    request : GET   /yungou/getTenZone
    
    params: {limit: 一次取多少条, page:第几页}
    
    success : {code : 200, data: [{yungou:云购对象,product:商品对象},{}...], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####找出十元专区云购的数量

    request : GET   /yungou/getCountTenZone
    
    params: {}
    
    success: {code : 200, data: count, msg: 获取成功}

####根据yungouID去最新一期的云购

    request : GET   /yungou/getLatestYungou

    params: {yid}

    success: {code : 200, data: id(最新一期云购ID, msg: 获取成功}

####根据productID去最新一期云购

    request : GET   /yungou/getLatestByProduct

    params : {pid}
    
    success : {code : 200, data: yungou, msg : 获取成功}
            
##Product

####获取推荐夺宝
    
    request : GET /product/recommend
    
    params : {limit : 数量}
    
    success : {code : 200, data : [{商品信息},{}....], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
	
##Order

####获取某个用户的某个云购订单

    request : GET /order/getByYungou
    
    params : {id : yungouId}
    
    success : { code : 200, data : [{order}], msg : 获取成功}
    
    error : { code : 403, data : [], msg : 未登录}
            { code : 403, data : [], msg : 未传参数}
            
####获取某个获奖订单和用户信息
    
    request : GET /order/winOrder
    
    params : {orderId : orderId,yungouId : yungouId(选填)}
             {1}
    
    success : {code : 200, data : [{userName,count,userId,loginArea}] , msg : 获取成功}
    
    error : {code : 403 , data : [], msg : 未传参数}
    
####获取某个用户的某个云购的夺宝号码

    request : GET /order/getNumbers
    
    params : {id: yungouId, userId: userId}
    
    success : {code : 200, data :{numberStart,numberEnd,buyTime}, msg : 获取成功}
    
    error : {code : 403, data :[], msg : 未传参数}
    
####获取某个云购的购买订单

    request : GET /order/orderByYungou
    
    params : {id: 云购id, page:第几页, now(选填):进入浏览器时间}
    
    success : {code : 200, data : [{buyTime,avatorUrl,loginArea,ip,userName,numberStart,numberEnd,userId,count},{}...], msg : 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}

####获取某个云购的购买订单(手机端)

    request : GET /order/orderByYungouMobile
    
    params : {yid: 云购id, limit:一次取几条, buyTime(选填):最后一条数据的购买时间}
    
    success : {code : 200, data : [{buyTime,avatorUrl,loginArea,ip,userName,numberStart,numberEnd,userId,count},{}...], msg : 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}
    
####获取某个云购成功订单的数量

    request : GET /order/getOrderCount
    
    params : {id: 云购id}
    
    success : {code : 200, data : count, msg : 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}
    
####获取用户的夺宝记录

    request : GET /order/getRecord
    
    params : {page:第几页, limit: 一次取多少数据,status(选填):云购状态,0代表正在进行，1代表正在揭晓，2代表已经开奖,3代表未开奖,id(选填):用户ID,不填默认取登录用户}
    
    success : {code : 200, data : [{thumbnailUrl,title,price,productId,term,yungouId,status,endTime,startTime,timeout,saleCount,isOn,numbers(用户夺宝号),winUser(夺宝人信息),count(用户参与人次)},{}...],  msg : 获取成功}
            numbers:[{numberStart,numbersEnd,buyTime:购买时间},{}..]
            winUser:{userName,loginArea,count,userId,result,buyTime}
             
    error : {code : 403, data : [], msg : 未传参数}
    
####获取用户的夺宝记录的次数

    request : GET /order/getCountRecord
    
    params : {｝
    
    success : {code : 200, data : {all,status0,status1,status2}, msg : 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}
    
####获取用户的幸运记录

    request : GET   /order/getWinRecord
    
    params : {page:第几页, limit : 一次多少条, id(选填):用户ID,不填默认是登录用户}
    
    success : {code :200, data : [{yungouId,productId,orderId,winOrderId,title,term,price,result,count,endTime,buyTime,logisticsStatus},{}...], msg : 获取成功}
    
    error : {code: 403, data: [], msg: 未传参数}
    
####获取用户的幸运记录总次数

    request : GET   /order/getCountWinRecord
    
    params : {}
    
    success : {code :200 , data: count, msg : 获取成功}
    
    error : {}
    
##shoppingCart

####购物车列表

    request : GET /shoppingCart/list
    
    success : {code : 200 , data : [{购物车商品信息}] , msg : 获取成功}
    
    error : {code : 403 , data : [], msg : 未登录}
    
####修改购物车某商品云购次数

    request : POST /shoppingCart/add
    
    params : {productId : 商品id, amount : 增量（分正/负数）}
             {
                productId : 1,
                amount :10/-10
             }
    
    success : {code : 200 , data : true , msg : 插入成功}
    
    error : {code : 403 , data : [] , msg : '未登录'}
            {code : 405 , data : [] , msg : '未传参数'}
            
####删除购物车某商品

    request : POST /shoppingCart/remove
    
    params : {productId : 商品id}
             {productId : 1}
    
    success : {code : 200 , data : true , msg : 删除成功}
    
    error : {code : 403 , data : [] , msg : '未登录'}
            {code : 405 , data : [] , msg : '未传参数'}
            
####进入购物车结算页面,获取购物车列表

    request : GET /shoppingCart/intoCart
    
    params : {}
    
    success : {code : 200 , data : [{title,price,amount,productId,term,yungouId,singlePrice,thumbnailUrl},{}...] , msg : 获取成功}
    
    error : {code : 403 , data : [] , msg : 未登录}

####批量删除购物车

    request : POST /shoppingCart/removeLot
    
    params : {ids : [shoppingCartId数组]}
             {
                ids : [1,2,3...]
             }
    
    success : {code : 200 , data : true , msg : 删除成功}
    
    error : {code : 403 , data : false , msg : 删除失败}
            {code : 405 , data : [], msg : 未传参数}
            
##payment

####进行支付

    request : POST /pay
    
    params : {cashierid : 收银的ID}
    
    success : {code : 200 , data : [], msg : 全部订单支付成功}
              {code : 200 , data : [], msg : 部分订单支付成功}
              
    error : {code : 403, data : [], msg : 未登录}
            {code : 405, data : [], msg : 订单已经支付过了}
            {code : 405, data : [], msg : 支付失败}
            
##cashier

####提交订单，产生收银

    request : POST /cashier/add
    
    params : {orders : [{productId,yungouId,count},{}...]}
             {
                orders : [{
                    productId : 1,
                    yungouId : 1,
                    count : 10
                },{}....]
             }
    
    success : {code : 200, data : cashierid , msg : 插入成功}
    
    error : {code : 403, data : [], msg : 未传参数}
    
####获取cashier对应的订单详情

    request : GET   /cashier/getDetail
    
    params : {cashierid : 收银ID }
    
    success : {code : 200, data : [{status:订单状态,buyTime:支付时间,productId,title:商品标题,term:云购期数,count:购买人次,numberStart,numberEnd},{}....], msg : '获取成功'}
    
    error : {code : 403, data: [], msg: 未传参数}

####获取cashier对应的订单详情(手机端)

    request : GET   /cashier/getDetailMobile
    
    params : {cashierid : 收银ID }
    
    success : {code : 200, data : [{payStatus,status:订单状态,buyTime:支付时间,productId,title:商品标题,term:云购期数,count:购买人次,numberStart,numberEnd,yungouId},{}....], msg : '获取成功'}
    
    error : {code : 403, data: [], msg: 未传参数}
    
##show

####获取所有晒单

    request : GET /show/getAll
    
    params : {firstId: 最新一条数据的ID, lastId : 最后一条数据的ID, limit : 一次取多少条(不填默认40条)}
    
    success : {code : 200, data : [{showId:晒单ID ,avatorUrl:用户头像, endTime:云购揭晓时间, count: 用户购买人次, title:晒单标题,result:中奖号码,productTitle:商品标题,userName:用户名,userId:用户ID,term:期数,productId:商品ID,yungouId:云购ID,createdTime:晒单创建时间,content:晒单内容,imgUrls:[晒单图片url数组]},{},...], msg : 获取成功}
    
    error : {code : 405, data : [], msg : 数据为空}
    
####获取晒单的数量

    request : GET   /show/getCountAll
    
    params : {}
    
    success : {code : 200, data : count, 获取成功}
    
    error : {}
    
####获取某个商品的晒单

    request : GET /show/getByProduct
    
    params : {productId: 商品ID, lastId : 最后一条记录的ID(选填), limit :数量}
    
    success : {code :200, data : [{showId,avatorUrl,userName,userId,title(晒单标题),createdTime(晒单创建时间),content,imgUrls:[晒单图片url数组],productId,yungouId},{}.           ...], msg : 获取成功}
    
    error : {code : 403, data : array(), msg: 未传参数}

####获取某个云购的晒单数量
    
    request : GET /show/getCountByYungou
    
    params : {yungouId : 云购ID}
    
    success : {code : 200, data: count, msg : 获取成功}
    
    error : {code :403, data : array(), msg : 未传参数}
    
####获取某个晒单的详细信息

    request : GET /show/getDetail
    
    params : {showId : 晒单ID , yungouId : 云购ID (两者二选一)}
    
    success : {code : 200, data : {avatorUrl,userName,userId,title,createdTime,count,result,endTime,productTitle,term,price,content,imgUrls:[晒单图片url数组],thumbnailUrl,userId,yungouId,productId}, msg: 获取成功}
    
    error : {code : 403, data : [], msg : 未传参数}
            {code : 405, data : [], msg : 数据为空}
            
####获取某个用户的晒单

    request : GET   /show/getAllByUser
    
    params : {userId(选填，不填默认登录用户) : 用户ID, limit: 数量, page : 第几页}
    
    success : {code : 200, data : [{title:晒单标题,result:中奖号码,productTitle:商品标题,term:期数,productId:商品ID,yungouId:云购ID,createdTime:晒单创建时间,content:晒单内容,imgUrls:[晒单图片url数组]},{},...], msg : 获取成功}
    
####获取某个用户晒单的数量

    request : GET   /show/getCountAllByUser
    
    params : {userId : 用户ID}
    
    success : {code : 200, data : count, msg: 获取成功}
    
    error : {}
    
####插入晒单记录

    request : POST  /show/add
    
    params : {title, content, imgUrls, yungouId, orderId, term}
    
    success : {code : 200, data: true, msg : 插入成功}
    
    error : {code : 403, data : [], msg: 未传参数}
    
####获取某个用户已收货，未晒单的订单的相关信息

    request : GET  /show/getUnshowByUser
    
    params : { limit: 数量, page : 第几页 }
    
    success : {code : 200, data: [{productImage:商品图片,productTitle:商品标题,term:期数,yungouId:云购ID,orderId:订单Id},{},...], msg : 获取成功}
    
    error : {code : 404, data : [], msg: 获取失败}

##address

####获取所有省份

    request : GET /address/province
    
    params : {}
    
    success : {code : 200, data : {{id,provinceID,province},{},....}, msg: 获取成功}
    
    error : {}
    
####获取某个省份所有城市

    request : GET /address/city
    
    params : {provinceId: 省份的ID}
    
    success : {code : 200, data : [{id,cityID,city,father},{}...], msg :获取成功}
    
    error : {code : 403, data :[], msg : 未传参数}
    
####获取某个城市的地区

    request : GET /address/area
    
    params : {cityId: 城市的ID}
    
    success : {code : 200, data : [{id,areaID,area,father},{}...], msg: 获取成功}
    
    error : {code :403, data: [], msg : 未传参数}
    
####添加收货地址

    request : POST /address/add
    
    params : {provinceID,cityID,areaID,street,receiver,phoneNumber,status,idCode(选填),postCode(选填)}
    
    success : {code : 200, data : addressId, msg : 插入成功}
    
    error : {code : 403, data: [], msg : 未传参数}

####添加收货地址(手机端)

    request : POST /address/addMobile
    
    params : {provinceID,cityID,areaID,street,receiver,phoneNumber,status,idCode(选填),postCode(选填)}
    
    success : {code : 200, data : {address,province,city,area}, msg : 插入成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 404, data: [], msg : 插入失败}
            {code : 405, data : false msg : 添加超过5条,失败};
    
####删除收货地址

    request : POST /address/remove
    
    params : {id:地址id}
    
    success : {code: 200, data: true, msg : 删除成功}
    
    error : {code : 403, data: [], msg: 未传参数}
    
####修改收货地址
    
    request : POST /address/update
    
    params : {address: 地址对象,一定要有address的id}
    
    success : {code : 200, data : true, msg : 修改成功}
    
    error: {code : 403, data: {address,province,city,area}, msg : 未传参数}
           {code : 404, data: [], msg : 修改失败}
    
####获取用户所有地址

    request : GET /address/getAll
    
    params : {}
    
    success : {code : 200, data : [{address,province,area,city},{}...], msg : 获取成功}
    
    error : {code : 403, data : [], msg: 未传参数}
    
####获取用户某个地址

    request : GET /address/getById
    
    params : {id:地址id}
    
    success : {code : 200 ,data : {address,province,area,city}, msg : 获取成功}
    
    error :  {code : 403, data: [], msg: 未传参数}
             {code : 404, data: [], msg: 数据为空}
    
##Credit

####获取积分记录

    request : GET /credit/getRecord
    
    params : {page: 第几页, limit: 一次取多少数据}
    
    success : {code : 200, data: [{credit},{},...], msg : 获取成功}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####用户签到

    request : GET   /credit/signIn
    
    params : {}
    
    success : {code : 200, data:true, msg: 签到成功}
    
    error : {code: 405 , data: [], msg: 已经签到过了}
            {code : 406, data: false, msg: 签到失败}
            
####判断用户是否已经签到

    request : GET   /credit/isSignIn
    
    params : {}
    
    success : {code : 200, data: [], msg : 已经签到}
    
    error : {code : 405, data: [], msg : 还未签到}
    
####积分兑换余额

    request : POST   /credit/exchange
    
    params : {amount:要兑换的积分数量}
    
    success : {"code":200,"data":{"credits":"251143","balance":"704"},"msg":"积分兑换成功"}
    
    error :     {code : 403, data: [], msg : 未传参数}
                {"code":400,"data":{"credits":"251143","balance":"704"},"msg":"积分兑换失败"}
                {code : 401, data: [], msg : 积分剩余不足}
                {"code":402,"data":[],"msg":"兑换的积分数太少"}
    
##Banner

####获取所有轮播图

    request : GET   /banner/getAll
    
    params : {}
    
    success : {code :200, data :[{banner},{}...], msg : 获取成功}
    
    error :{}
    
##WinOrder

####根据winOrderId获取winOrder详细信息(手机端)

    request : GET  /winOrder/getById
    
    params: {winOrderId}
    
    success : {code : 200 , data : {winOrder}, msg : 修改成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 404, data: [], msg : 查找不到此信息}

####确认收货，修改对应状态

    request : POST  /winOrder/confirm
    
    params: {winOrderId}
    
    success : {code : 200 , data : true, msg : 修改成功}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####确认收货地址

    request : GET  /winOrder/confirmAddress
    
    params: {winOrderId , addressId}
    
    success : {code : 200 , data : true, msg : 确认收货地址成功}
    
    error : {code : 403, data: [], msg : 未传参数}
            {code : 407, data: [], msg : 确认收货地址失败}
            
            
##微信相关接口

####获取wx.config

    request : GET  /wx/getJsApiConfig
    
    params: {url}
    
    success : {code : 200 , data:{"appId":appid,"timestamp":1463043555,"nonceStr":"1KLdfBRcv0tjbTsN","signature":"ce9264347be778afa7ae2c75b133016d9fee60d1"},msg:"获取jsApiConfig成功"}
    
####发起微信登陆

	request	:	GET /wx/login
	
	params	:	{ returnAddress:登录后跳转的页面(只存入mobile.html#后面的内容，如：要跳转到http://网站根目录/mobile.html#/tab/index，则returnAddress应为/tab/index)}

    success : 微信登陆并跳转到returnAddress
    
    error : {code : 403, data: [], msg : 参数错误}
    
    
    
##Delegate

####获取提现记录

    request : GET  /admin/delegate/getWithdrawRecords
    
    params: { delegateid:具有代理身份的用户的用户id,值为0表示获取所有用户记录 , isapproved：提现是否被批准（0未批准，1已批准，2全部），page，limit}
    
    success : {"code":200,"data":[  {"id":"7","delegateId":"41","time":"2016-05-17 10:23:40","amount":"9999","isApproved":"0"}  ],"msg":"获取提现记录成功"}
    
               {"code":403,"data":[],"msg":"参数不全"}
               
####获取提现记录数目

    request : GET  /admin/delegate/getWithdrawCount
    
    params: { delegateid:具有代理身份的用户的用户id,值为0表示获取所有用户记录 , isapproved：提现是否被批准（0未批准，1已批准，2全部）}
    
    success : {"code":200,"data":"11","msg":"获取提现记录数目成功"}
    
               {"code":403,"data":[],"msg":"参数不全"}
    
####批准提现

	request	:	POST /admin/delegate/approveWithdraw
	
	params	:	{ withdrawid }

    success : {"code":200,"data":[],"msg":"批准成功"}
    
    error : {code : 403, data: [], msg : 未传参数}
    
####添加代理

	request	:	POST /admin/delegate/addDelegate
	
	params	:	{ userid }

    success : {"code":200,"data":[],"msg":"新增代理成功"}
    
    error : {"code":405,"data":[],"msg":"用户已经是代理"}
            {"code":404,"data":[],"msg":"没有此用户"}
            {"code":406,"data":[],"msg":"更新user表失败"}
            
            
####获取总会员数和钱包余额

	request	:	GET /delegate/getMemberNumAndCash
	
	params	:	{}

    success : {"code":200,"data":{"memberNum":"1","cash":0.1},"msg":"获取总会员数和钱包余额成功"}
    
            
####获取当月数据

	request	:	POST /delegate/getThisMonthStatics
	
	params	:	{}

    success : {"code":200,"data":{"monthRegister":新注册,"rate":返佣比例,"monthCount":当月流水},"msg":"获取当月数据成功"}
    
            
####申请提现

	request	:	POST /delegate/applyWithdraw
	
	params	:	{ amount：要申请的提现金额数 }

    success : {"code":200,"data":[],"msg":"申请成功"}
    
    error : {"code":405,"data":[],"msg":"余额不足"}
            {"code":403,"data":[],"msg":"参数不全"}

            
####获取提现记录

	request	:	GET /delegate/getWithdrawRecords
    
	params	:	{ isapproved：提现是否被批准（0未批准，1已批准，2全部），page，limit }

    success : {"code":200,"data":[{"id":提现记录id,"userName":"22222","delegateId":"12","time":"2016-05-17 14:07:50","amount":"0","isApproved":"1"}],"msg":"获取提现记录成功"}
    
    error : {"code":403,"data":[],"msg":"参数不全"}
    
