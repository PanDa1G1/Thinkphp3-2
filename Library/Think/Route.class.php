<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * ThinkPHP路由解析类
 */
class Route {
    
    // 路由检测
    public static function check(){
        $depr   =   C('URL_PATHINFO_DEPR');//函数获取全局配置，这里是获取配置文件的pathinfo的分隔符'/'
        $regx   =   preg_replace('/\.'.__EXT__.'$/i','',trim($_SERVER['PATH_INFO'],$depr));
        // 分隔符替换 确保路由定义使用统一的分隔符
        if('/' != $depr){
            $regx = str_replace($depr,'/',$regx);
        }
        // URL映射定义（静态路由）
        $maps   =   C('URL_MAP_RULES');
        if(isset($maps[$regx])) {
            $var    =   self::parseUrl($maps[$regx]);//将url里面的控制器参数等分开存入数组，返回数组
            $_GET   =   array_merge($var, $_GET);
            return true;                
        }

        /*
         如果如果静态路由没有匹配成功就会进入动态路由进行检测，首先从配置文件中获取路由规则的数组
         一般路由规则有两种情况
            1）'路由表达式'=>'路由地址和传入参数'
            2)array('路由表达式','路由地址'，'传入参数')
         所以遍历的时候要分开处理，通过判断$rule是不是数字来判断是第一种还是第二种
            第一种的$rule为路由规则，即为字符
            第二种为数字。如果是第二种，就从数组中依次出栈，取第一个元素，此时$rule就是匹配规则
        */
        // 动态路由处理
        $routes =   C('URL_ROUTE_RULES');//获取config.php里面的路由规则
        if(!empty($routes)) {
            foreach ($routes as $rule=>$route){
                if(is_numeric($rule)){
                    // 支持 array('rule','adddress',...) 定义路由
                    $rule   =   array_shift($route);//rule是$route数组的第一个元素，依次出栈
                }// 路由参数
                if(is_array($route) && isset($route[2])){
                    /*检测是否对url进行了限制，如果进行了限制$route[2]就是一个数组，如果利用array进行路由地址和参数的匹                     配的话，还可以添加第三个参数，即为所需要添加的限制条件(后缀，方法，自定义的函数)*/
                    $options    =   $route[2];
                    if(isset($options['ext']) && __EXT__ != $options['ext']){
                        // URL后缀检测
                        continue;
                    }
                    if(isset($options['method']) && REQUEST_METHOD != strtoupper($options['method'])){
                        // 请求类型检测
                        continue;
                    }
                    // 自定义检测
                    if(!empty($options['callback']) && is_callable($options['callback'])) {
                        if(false === call_user_func($options['callback'])) {
                            continue;
                        }
                    }                    
                }
                /*通过判断规则的第一个字符是不是'/'来判断是正则匹配还是规则匹配
                    第一种情况为闭包，进入特定的函数后，将参数传入函数，判断返还值，如果是bool值则继续
                    第二种情况为传递控制器/方法，传入第二个函数，将传递的参数替换到url里。*/
                if(0===strpos($rule,'/') && preg_match($rule,$regx,$matches)) {
                    /*Closure表示匿名函数的类，第一个if判断是不是闭包传递，此时的$route是一个匿名函数*/
                    if($route instanceof \Closure) {
                        // 执行闭包
                        $result = self::invokeRegx($route, $matches);
                        // 如果返回布尔值 则继续执行
                        return is_bool($result) ? $result : exit;
                    }else{
                        return self::parseRegex($matches,$route,$regx);//不是闭包就进行正则匹配，这里将
                    }
                }else{ // 规则路由

                    $len1   =   substr_count($regx,'/');
                    $len2   =   substr_count($rule,'/');
                    if($len1>=$len2 || strpos($rule,'[')) {
                        if('$' == substr($rule,-1,1)) {// 完整匹配
                            if($len1 != $len2) {
                                continue;
                            }else{
                                $rule =  substr($rule,0,-1);
                            }
                        }

                        $match  =  self::checkUrlMatch($regx,$rule);
                        if(false !== $match)  {
                            if($route instanceof \Closure) {
                                // 执行闭包
                                $result = self::invokeRule($route, $match);
                                // 如果返回布尔值 则继续执行
                                return is_bool($result) ? $result : exit;
                            }else{
                                return self::parseRule($rule,$route,$regx);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    // 检测URL和规则路由是否匹配
    /*此函数主要用来检测规则路由和url是否匹配。传入的第一个参数是url，第二个参数是规则路由。
                     基本逻辑：
                    首先用explore函数把要检测的url值和规则路由按照/进行拆分。
                    遍历拆分后的规则路由数组，一一处理每个元素。
                    按照先特殊后普通的原则。
                    首先实现路由匹配的可选定义[:month\d]变量用[ ]包含起来后就表示该变量是路由匹配的可选变量。
                    如果要检测的元素字符串以[:开头，就截取出中括号中间的字符串赋值给$val，即:month\d.
                    下面是规则排除的实现逻辑。
                    如果$val是以：开头的，就检测$val字符串中|的位置,然后把|之前的内容取出来。之后取出\后的内容，判断是否为                   d，然后进行is_numberic()判断
    */
    private static function checkUrlMatch($regx,$rule) {
        $m1 = explode('/',$regx);
        $m2 = explode('/',$rule);
        $var = array();         
        foreach ($m2 as $key=>$val){
            if(0 === strpos($val,'[:')){
                $val    =   substr($val,1,-1);//取出:month/d
            }
                
            if(':' == substr($val,0,1)) {// 动态变量
                if($pos = strpos($val,'|')){//判断是否有函数
                    // 使用函数过滤
                    $val   =   substr($val,1,$pos-1);
                }
                if(strpos($val,'\\')) {
                    $type = substr($val,-1);//取出\后的内容
                    if('d'==$type) {
                        if(isset($m1[$key]) && !is_numeric($m1[$key]))//判断是否为数字
                            return false;
                    }
                    $name = substr($val, 1, -2);
                }elseif($pos = strpos($val,'^')){
                    $array   =  explode('-',substr(strstr($val,'^'),1));
                    if(in_array($m1[$key],$array)) {
                        return false;
                    }
                    $name = substr($val, 1, $pos - 1);
                }else{
                    $name = substr($val, 1);
                }
                $var[$name] = isset($m1[$key])?$m1[$key]:'';
            }elseif(0 !== strcasecmp($val,$m1[$key])){
                return false;
            }
        }
        // 成功匹配后返回URL中的动态变量数组
        return $var;
    }

    // 解析规范的路由地址
    // 地址格式 [控制器/操作?]参数1=值1&参数2=值2...
    /*首先判断url里面是否通过?传递了参数，如果有? 则将其分割，并存入$info 数组，然后将其中的参数都变为php变量
     该函数是将url里面的控制器、参数等根据'/'分割然后分别存入数组*/
    private static function parseUrl($url) {
        $var  =  array();
        if(false !== strpos($url,'?')) { // [控制器/操作?]参数1=值1&参数2=值2...
            $info   =  parse_url($url);
            $path   = explode('/',$info['path']);//将地址根据'/'分割
            parse_str($info['query'],$var);//将参数转化为php变量
        }elseif(strpos($url,'/')){ // [控制器/操作]
            $path = explode('/',$url);
        }else{ // 参数1=值1&参数2=值2...
            parse_str($url,$var);
        }
        if(isset($path)) {
            $var[C('VAR_ACTION')] = array_pop($path);
            if(!empty($path)) {
                $var[C('VAR_CONTROLLER')] = array_pop($path);
            }
            if(!empty($path)) {
                $var[C('VAR_MODULE')]  = array_pop($path);
            }
        }
        return $var;
    }

    // 解析规则路由
    // '路由规则'=>'[控制器/操作]?额外参数1=值1&额外参数2=值2...'
    // '路由规则'=>array('[控制器/操作]','额外参数1=值1&额外参数2=值2...')
    // '路由规则'=>'外部地址'
    // '路由规则'=>array('外部地址','重定向代码')
    // 路由规则中 :开头 表示动态变量
    // 外部地址中可以用动态变量 采用 :1 :2 的方式
    // 'news/:month/:day/:id'=>array('News/read?cate=1','status=1'),
    // 'new/:id'=>array('/new.php?id=:1',301), 重定向

    /*该函数首先对*/
    private static function parseRule($rule,$route,$regx) {
        // 获取路由地址规则
        $url   =  is_array($route)?$route[0]:$route;
        // 获取URL地址中的参数
        $paths = explode('/',$regx);
        // 解析路由规则
        $matches  =  array();
        $rule =  explode('/',$rule);
        foreach ($rule as $item){
            $fun    =   '';
            if(0 === strpos($item,'[:')){
                $item   =   substr($item,1,-1);
            }//取出[]里面的内容
            if(0===strpos($item,':')) { // 动态变量获取
                if($pos = strpos($item,'|')){ 
                    // 支持函数过滤
                    $fun  =  substr($item,$pos+1);
                    $item =  substr($item,0,$pos);                    
                }
                if($pos = strpos($item,'^') ) {
                    $var  =  substr($item,1,$pos-1);
                }elseif(strpos($item,'\\')){
                    $var  =  substr($item,1,-2);
                }else{
                    $var  =  substr($item,1);
                }
                $matches[$var] = !empty($fun)? $fun(array_shift($paths)) : array_shift($paths);
            }else{ // 过滤URL中的静态变量
                array_shift($paths);
            }
        }

        if(0=== strpos($url,'/') || 0===strpos($url,'http')) { // 路由重定向跳转
            if(strpos($url,':')) { // 传递动态参数
                $values = array_values($matches);
                $url = preg_replace_callback('/:(\d+)/', function($match) use($values){ return $values[$match[1] - 1]; }, $url);//进行参数的转化
            }
            header("Location: $url", true,(is_array($route) && isset($route[1]))?$route[1]:301);
            exit;
        }else{
            // 解析路由地址
            $var  =  self::parseUrl($url);
            // 解析路由地址里面的动态参数
            $values  =  array_values($matches);
            foreach ($var as $key=>$val){
                if(0===strpos($val,':')) {
                    $var[$key] =  $values[substr($val,1)-1];
                }
            }
            $var   =   array_merge($matches,$var);
            // 解析剩余的URL参数
            if(!empty($paths)) {
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){ $var[strtolower($match[1])]=strip_tags($match[2]);}, implode('/',$paths));
            }
            // 解析路由自动传入参数
            if(is_array($route) && isset($route[1])) {
                if(is_array($route[1])){
                    $params     =   $route[1];
                }else{
                    parse_str($route[1],$params);
                }                
                $var   =   array_merge($var,$params);
            }
            $_GET   =  array_merge($var,$_GET);
        }
        return true;
    }

    // 解析正则路由
    // '路由正则'=>'[控制器/操作]?参数1=值1&参数2=值2...'
    // '路由正则'=>array('[控制器/操作]?参数1=值1&参数2=值2...','额外参数1=值1&额外参数2=值2...')
    // '路由正则'=>'外部地址'
    // '路由正则'=>array('外部地址','重定向代码')
    // 参数值和外部地址中可以用动态变量 采用 :1 :2 的方式
    // '/new\/(\d+)\/(\d+)/'=>array('News/read?id=:1&page=:2&cate=1','status=1'),
    // '/new\/(\d+)/'=>array('/new.php?id=:1&page=:2&status=1','301'), 重定向
    private static function parseRegex($matches,$route,$regx) {
        // 获取路由地址规则
        $url   =  is_array($route)?$route[0]:$route;
        $url   =  preg_replace_callback('/:(\d+)/', function($match) use($matches){return $matches[$match[1]];}, $url); //获取$route里面的:id参数,同时将规则里面的:id占位符进行替换
        if(0=== strpos($url,'/') || 0===strpos($url,'http')) { // 路由重定向跳转
            header("Location: $url", true,(is_array($route) && isset($route[1]))?$route[1]:301);
            exit;
        }else{
            // 解析路由地址
            $var  =  self::parseUrl($url);
            // 处理函数
            foreach($var as $key=>$val){
                if(strpos($val,'|')){
                    list($val,$fun) = explode('|',$val);
                    $var[$key]    =   $fun($val);
                }
            }
            // 解析剩余的URL参数
            $regx =  substr_replace($regx,'',0,strlen($matches[0]));
            if($regx) {
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){
                    $var[strtolower($match[1])] = strip_tags($match[2]);
                }, $regx);
            }
            // 解析路由自动传入参数
            if(is_array($route) && isset($route[1])) {
                if(is_array($route[1])){
                    $params     =   $route[1];
                }else{
                    parse_str($route[1],$params);
                }
                $var   =   array_merge($var,$params);
            }
            $_GET   =  array_merge($var,$_GET);
        }
        return true;
    }

    // 执行正则匹配下的闭包方法 支持参数调用
    static private function invokeRegx($closure, $var = array()) {
        $reflect = new \ReflectionFunction($closure);//ReflectionFunction报告一个函数的有关信息，$closure是一个函数
        $params  = $reflect->getParameters();//获取函数的参数
        $args    = array();
        array_shift($var);//正则匹配的结果逐个出栈
        foreach ($params as $param){//遍历参数
            if(!empty($var)) {
                $args[] = array_shift($var);
            }elseif($param->isDefaultValueAvailable()){
                $args[] = $param->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($args);//使用数组给方法传送参数，并执行函数
    }

    // 执行规则匹配下的闭包方法 支持参数调用
    static private function invokeRule($closure, $var = array()) {
        $reflect = new \ReflectionFunction($closure);
        $params  = $reflect->getParameters();
        $args    = array();
        foreach ($params as $param){
            $name = $param->getName();
            if(isset($var[$name])) {
                $args[] = $var[$name];
            }elseif($param->isDefaultValueAvailable()){
                $args[] = $param->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($args);
    }

}