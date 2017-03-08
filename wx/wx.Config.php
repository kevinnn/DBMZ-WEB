<?php


class WxConfig
{
    
    const appID = 'wx5978bb99a4b31667';    
    const appsecret = '757001c95358441f7206c6d7614a40d3';
    
    
    // get
    // https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
    // {"access_token":"htU73SIpKfvuMaVqR7g6Sx8x5uAhF46rzvPVIl39I7zKG-2aBk79s4lEzQ6L_ecWN0OWzU30Sx6NSpC7Ja-YSHUhF9xciRn6JIkRxIa3pnQwbyuPIVdHzya3SbY7rl5HIPTfAEANUY","expires_in":7200}
    
    // get
    // https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi
    //{"errcode":0,"errmsg":"ok","ticket":"kgt8ON7yVITDhtdwci0qef_HpWFvYttfK-MfETXpTTmmOBi0T75pqokxA8gmJwZG1k5JRXig9fwanUQ2uwEVkQ","expires_in":7200}
}