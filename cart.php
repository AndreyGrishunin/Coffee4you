<?php
define('myeshop', true);	
include("db_connect.php");
include("container.php");
include("center.php");
include("function.php");
include("assets/function/functions.php");

$id = clear_string($_GET["id"]);
$action = clear_string($_GET["action"]);
    
   
    if ($_SESSION['auth'] == 'yes_auth')
    {
        switch ($action) 
        {
        case 'clear':
            $clear = mysql_query("DELETE FROM cart WHERE cart_ip = '{$_SESSION['auth_id']}'");     
            break;
            
            case 'delete':     
            $delete = mysql_query("DELETE FROM cart WHERE cart_id = '$id' AND cart_ip = '{$_SESSION['auth_id']}'");        
            break;
        }
    }
    else if ($_SESSION['auth'] == 'no_auth')
    {
        switch ($action) 
        {
        case 'clear':
            $clear = mysql_query("DELETE FROM cart WHERE cart_ip = '99'");     
            break;
            
            case 'delete':     
            $delete = mysql_query("DELETE FROM cart WHERE cart_id = '$id' AND cart_ip = '99'");        
            break;        
    }           
	}
    
if (isset($_POST["submitdata"]))
{
if ( $_SESSION['auth'] == 'yes_auth' ) 
 {
        
    mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_name,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '".$_POST["order_delivery"]."',					
							'".$_SESSION['auth_name']."',
                            '".$_SESSION['auth_address']."',
                            '".$_SESSION['auth_phone']."',
                            '".$_POST['order_note']."',
                            '".$_SESSION['auth_email']."'                              
						    )");         

 }else
 {
$_SESSION["order_delivery"] = $_POST["order_delivery"];
$_SESSION["order_name"] = $_POST["order_name"];
$_SESSION["order_email"] = $_POST["order_email"];
$_SESSION["order_phone"] = $_POST["order_phone"];
$_SESSION["order_address"] = $_POST["order_address"];
$_SESSION["order_note"] = $_POST["order_note"];

    mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '".clear_string($_POST["order_delivery"])."',					
							'".clear_string($_POST["order_fio"])."',
                            '".clear_string($_POST["order_address"])."',
                            '".clear_string($_POST["order_phone"])."',
                            '".clear_string($_POST["order_note"])."',
                            '".clear_string($_POST["order_email"])."'                   
						    )"); 
 }

                          
 $_SESSION["order_id"] = mysql_insert_id();                          
                            
$result = mysql_query("SELECT * FROM cart WHERE cart_ip = '99'");
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);    

do{

    mysql_query("INSERT INTO buy_products(buy_id_order,buy_id_product,buy_count_product)
						VALUES(	
                            '".$_SESSION["order_id"]."',					
							'".$row["cart_id_product"]."',
                            '".$row["cart_count"]."'                   
						    )");



} while ($row = mysql_fetch_array($result));
}?>
<script type="text/javascript">document.location.href = "cart.php?action=completion"</script>
<?php                          
}      


$result = mysql_query("SELECT * FROM cart,table_product WHERE cart.cart_ip = '99' AND table_product.products_id = cart.cart_id_product");
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

do
{ 
$int = $int + ($row["price"] * $row["cart_count"]); 
}
 while ($row = mysql_fetch_array($result));
 

   $itogpricecart = $int;
}     ?>
<div class="container_for_product">
<?php
$action = clear_string($_GET["action"]);
switch ($action) {

      case 'oneclick':
 
 echo ' 
 <div class="container">  
 <div id="block-step">  
 <div id="name-step">  
 <ul>
 <li><a class="active" >1. Корзина товаров</a></li>
 <li><span>&rarr;</span></li>
 <li><a>2. Контактная информация</a></li>
 <li><span>&rarr;</span></li>
 <li><a>3. Завершение</a></li> 
 </ul>  
 </div>  
 <p>шаг 1 из 3</p>
 <div class="btn_submit1" href="cart.php?action=clear">Очистить</div>
 </div>
 </div>
';

 
if ($_SESSION['auth'] == 'yes_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE cart.cart_ip = '{$_SESSION['auth_id']}' AND table_product.products_id = cart.cart_id_product");      
}
else if ($_SESSION['auth'] == 'no_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE table_product.products_id = cart.cart_id_product;");          
}   

If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

   echo '
   <div id="header-list-cart">    
   <div id="head1" >Изображение</div>
   <div id="head2" >Наименование товара</div>
   <div id="head3" >Кол-во</div>
   <div id="head4" >Цена</div>
   </div> 
   ';

do
{

$int = $row["cart_price"] * $row["cart_count"];
$all_price = $all_price + $int;
if ($all_price <= 0)
{
$int = $row["cart_price"] * $row["cart_count"];
$all_price = $all_price + $int;
}
else
{
    exit;
}

if  (strlen($row["image"]) > 0 && file_exists("assets/upload_img/coffee/".$row["image"]))
{
$img_path = 'assets/upload_img/coffee/'.$row["image"];
$max_width = 100; 
$max_height = 100; 
 list($width, $height) = getimagesize($img_path); 
$ratioh = $max_height/$height; 
$ratiow = $max_width/$width; 
$ratio = min($ratioh, $ratiow); 

$width = intval($ratio*$width); 
$height = intval($ratio*$height);    
}else
{
$img_path = "assets/img/no-images.jpeg";
$width = 120;
$height = 105;
} 

echo '

<div class="block-list-cart">

<div class="img-cart">
<p align="center"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></p>
</div>

<div class="title-cart">
<p><a href="">'.$row["title"].'</a></p>
<p class="cart-mini-features">
'.$row["mini_features"].'
</p>
</div>

<div class="count-cart">
<ul class="input-count-style">

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-minus">-</p>
</li>

<li>
<p align="center"><input id="input-id'.$row["cart_id"].'" iid="'.$row["cart_id"].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p>
</li>

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-plus">+</p>
</li>

</ul>
</div>

<div id="tovar'.$row["cart_id"].'" class="price-product"><h5><span class="span-count" >'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'" >'.group_numerals($int).' руб</p></div>
<div class="delete-cart"><a  href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="assets/img/bsk_item_del.png" /></a></div>

<div id="bottom-cart-line"></div>
</div>


';

    
}
 while ($row = mysql_fetch_array($result));
 
 echo '
 <h2 class="itog-price" align="right">Итого: <strong>'.group_numerals($all_price).'</strong> руб</h2>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Далее</a></p> 
 ';
  
} 
else
{
    echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
}


   
	    break;
        
        case 'confirm':     
     
    echo ' 
   <div id="block-step"> 
   <div id="name-step">  
   <ul>
   <li><a href="cart.php?action=oneclick" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a class="active" >2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a>3. Завершение</a></li> 
   </ul>  
   </div> 
   <p>шаг 2 из 3</p>

   </div>

   '; 
   

if ($_SESSION['order_delivery'] == "По почте") $chck1 = "checked";
if ($_SESSION['order_delivery'] == "Курьером") $chck2 = "checked";
if ($_SESSION['order_delivery'] == "Самовывоз") $chck3 = "checked"; 
 
 echo '

<h3 class="title-h3" >Способы доставки:</h3>
<form method="post">
<ul id="info-radio">
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery1" value="По почте" '.$chck1.'  />
<label class="label_delivery" for="order_delivery1">По почте</label>
</li>
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery2" value="Курьером" '.$chck2.' />
<label class="label_delivery" for="order_delivery2">Курьером</label>
</li>
<li>
<input type="radio" name="order_delivery" class="order_delivery" id="order_delivery3" value="Самовывоз" '.$chck3.' />
<label class="label_delivery" for="order_delivery3">Самовывоз</label>
</li>
</ul>
<h3 class="title-h3" >Информация для доставки:</h3>
<ul id="info-order">
';
  if ( $_SESSION['auth'] != 'yes_auth' ) 
{
echo '
<li><label for="order_fio"><span>*</span>ФИО</label><input type="text" name="order_fio" id="order_fio" value="'.$_SESSION["order_fio"].'" /><span class="order_span_style" >Пример: Иванов Иван Иванович</span></li>
<li><label for="order_email"><span>*</span>E-mail</label><input type="text" name="order_email" id="order_email" value="'.$_SESSION["order_email"].'" /><span class="order_span_style" >Пример: ivanov@mail.ru</span></li>
<li><label for="order_phone"><span>*</span>Телефон</label><input type="text" name="order_phone" id="order_phone" value="'.$_SESSION["order_phone"].'" /><span class="order_span_style" >Пример: 8 950 100 12 34</span></li>
<li><label class="order_label_style" for="order_address"><span>*</span>Адрес<br /> доставки</label><input type="text" name="order_address" id="order_address" value="'.$_SESSION["order_address"].'" /><span>Пример: г. Москва,<br /> ул Интузиастов д 18, кв 58</span></li>
';
}
echo '
<li><label class="order_label_style" for="order_note">Примечание</label><textarea name="order_note"  >'.$_SESSION["order_note"].'</textarea><span>Уточните информацию о заказе.<br />  Например, удобное время для звонка<br />  нашего менеджера</span></li>
</ul>
<p align="right" ><input type="submit" name="submitdata" id="confirm-button-next" value="Далее" /></p>
</form>


 ';      
      
        break;
        
        case 'completion': 

    echo ' 
   <div id="block-step"> 
   <div id="name-step">  
   <ul>
   <li><a href="cart.php?action=oneclick" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a href="cart.php?action=confirm" >2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a class="active" >3. Завершение</a></li> 
   </ul>  
   </div> 
   <p>шаг 3 из 3</p>

   </div>

<h3>Конечная информация:</3>
   '; 

if ( $_SESSION['auth'] == 'yes_auth' ) 
    {
echo '
<ul id="list-info" >
<li><strong>Способ доставки:</strong>'.$_SESSION['order_delivery'].'</li>
<li><strong>Email:</strong>'.$_SESSION['auth_email'].'</li>
<li><strong>Имя:</strong>'.$_SESSION['auth_name']. '</li>
<li><strong>Адрес доставки:</strong>'.$_SESSION['auth_address'].'</li>
<li><strong>Телефон:</strong>'.$_SESSION['auth_phone'].'</li>
<li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>
</ul>

';
   }else
   {
echo '
<ul id="list-info" >
<li><strong>Способ доставки:</strong>'.$_SESSION['order_delivery'].'</li>
<li><strong>Email:</strong>'.$_SESSION['order_email'].'</li>
<li><strong>Имя:</strong>'.$_SESSION['auth_name']. '</li>
<li><strong>Адрес доставки:</strong>'.$_SESSION['order_address'].'</li>
<li><strong>Телефон:</strong>'.$_SESSION['order_phone'].'</li>
<li><strong>Примечание: </strong>'.$_SESSION['order_note'].'</li>

</ul>

';    
}
 echo '
<h2 class="itog-price" align="right">Итого: <strong>'.$all_price.'</strong> руб</h2>
  <p align="right" class="button-next" ><a href="" >Оплатить</a></p> 
 
 '; 


		
        break;
        
	    default:  
		   
   echo ' 
   <div id="block-step">  
   <div id="name-step">  
   <ul>
   <li><a class="active" >1. Корзина товаров</a></li>
   <li><span>&rarr;</span></li>
   <li><a>2. Контактная информация</a></li>
   <li><span>&rarr;</span></li>
   <li><a>3. Завершение</a></li> 
   </ul>  
   </div>  
   <p>шаг 1 из 3</p>
   <div class="btn_submit1" href="cart.php?action=clear" >Очистить</div
   </div>
';
  
if ($_SESSION['auth'] == 'yes_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE table_product.products_id = cart.cart_id_product AND cart.cart_ip = '{$_SESSION['auth_id']}'");      
}
else if ($_SESSION['auth'] == 'no_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE cart.cart_ip = '99' AND table_product.products_id = cart.cart_id_product;");          
}  

If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

   echo '  
   <div id="header-list-cart">    
   <div id="head1" >Изображение</div>
   <div id="head2" >Наименование товара</div>
   <div id="head3" >Кол-во</div>
   <div id="head4" >Цена</div>
   </div> 
   ';

do
{

$int = $row["cart_price"] * $row["cart_count"];
$all_price = $all_price + $int;

if  (strlen($row["image"]) > 0 && file_exists("assets/upload_img/coffee/".$row["image"]))
{
$img_path = 'assets/upload_img/coffee/'.$row["image"];
$max_width = 100; 
$max_height = 100; 
 list($width, $height) = getimagesize($img_path); 
$ratioh = $max_height/$height; 
$ratiow = $max_width/$width; 
$ratio = min($ratioh, $ratiow); 

$width = intval($ratio*$width); 
$height = intval($ratio*$height);    
}else
{
$img_path = "assets/img/noimages.jpeg";
$width = 120;
$height = 105;
} 

echo '

<div class="block-list-cart">

<div class="img-cart">
<p align="center"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></p>
</div>

<div class="title-cart">
<p><a href="">'.$row["title"].'</a></p>
<p class="cart-mini-features">
'.$row["mini_features"].'
</p>
</div>

<div class="count-cart">
<ul class="input-count-style">

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-minus">-</p>
</li>

<li>
<p align="center"><input id="input-id'.$row["cart_id"].'" iid="'.$row["cart_id"].'" class="count-input" maxlength="3" type="text" value="'.$row["cart_count"].'" /></p>
</li>

<li>
<p align="center" iid="'.$row["cart_id"].'" class="count-plus">+</p>
</li>

</ul>
</div>

<div id="tovar'.$row["cart_id"].'" class="price-product"><h5><span class="span-count" >'.$row["cart_count"].'</span> x <span>'.$row["cart_price"].'</span></h5><p price="'.$row["cart_price"].'" >'.group_numerals($int).' руб</p></div>
<div class="delete-cart"><a  href="cart.php?id='.$row["cart_id"].'&action=delete" ><img src="assets/img/bsk_item_del.png" /></a></div>

<div id="bottom-cart-line"></div>
</div>


';

    
}
 while ($row = mysql_fetch_array($result));
 
 echo '
 <h2 class="itog-price" align="right">Итого: <strong>'.group_numerals($all_price).'</strong> руб</h2>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Далее</a></p> 
 ';
  
} 
else
{
    echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
}
        break;		
        
}
	
?>

</div>
</div>
</div>
</body>
</html>