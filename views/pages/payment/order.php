<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../components/head.php';?>
        <meta charset="UTF-8">
        <title>TODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body>
        <?php include '../../components/payment_header.php';?>
        <div class="order">
            <div class="m-header g-wrap f-clear" style="height: 127px;">
                <div class="m-header-logo" style="margin-top: 34px;">
                    <h1>
                    <a class="m-header-logo-link" href="/">一元夺宝</a>
                    </h1>
                </div>
                <div class="m-cart-order-steps">
                    <div class="w-step-duobao w-step-duobao-2"> </div>
                </div>
            </div>
            <div module="duobaoOrder/orderPage/OrderPage" class="m-duobao-order-list">
                <ul class="order-list">
                    <li class="order-list-header f-clear">
                        <div class="order-list-items-name items-goods-name">商品名称</div>
                        <div class="order-list-items-name items-goods-period">商品期号</div>
                        <div class="order-list-items-name items-goods-price">价值</div>
                        <div class="order-list-items-name items-goods-buyunit">夺宝价</div>
                        <div class="order-list-items-name items-goods-num">参与人次</div>
                        <div class="order-list-items-name items-goods-regular">参与期数</div>
                        <div class="order-list-items-name items-goods-total">小计</div>
                    </li>
                    <li class="order-list-items f-clear">
                        <div class="order-list-items-content items-goods-name">
                            <p>
                                <a href="/detail/975-304112333.html" target="_blank" title="中国黄金 9999投资金元宝 100g">中国黄金 9999投资金元宝 100g</a>
                            </p>
                        </div>
                        <div class="order-list-items-content items-goods-period f-items-center">304112333</div>
                        <div class="order-list-items-content items-goods-price f-items-center">30980夺宝币</div>
                        <div class="order-list-items-content items-goods-buyunit f-items-center">10夺宝币</div>
                        <div class="order-list-items-content items-goods-num f-items-center">10</div>
                        <div class="order-list-items-content items-goods-regular f-items-center">1</div>
                        <div class="order-list-items-content items-goods-total f-items-center">
                            <span>10夺宝币</span>
                        </div>
                    </li>
                    <li class="order-list-footer f-clear">
                        <span class="order-total txt-gray">商品合计：<strong>10</strong>&nbsp;夺宝币</span>
                    </li>
                </ul>
                <div class="m-coupon-options f-clear" pro="orderfooter"><div class="m-order-footer-msg" id="pro-view-1"><div class="footer-items pay-total">总需支付：<span class="footer-items-money"><strong>¥</strong><strong pro="total">9.00</strong></span></div><div class="m-order-operation f-clear" style=""><button class="w-button w-button-main w-button-xl" type="button" id="pro-view-2"><span>去支付</span></button></div></div></div>
                
            </div>
        </div>
        <?php include '../../components/footer.php';?>
    </body>
</html>