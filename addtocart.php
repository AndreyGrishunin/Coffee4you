
<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    define('myeshop', true);
    include("db_connect.php");
    include("assets/function/functions.php");
    
            
    $id = clear_string($_POST["id"]);
    $_SESSION['products_id']=$row["products_id_you"];
    
        if ($_SESSION['auth'] == 'yes_auth')
        {
            $result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SESSION['auth_id']}' AND cart_id_product = '{$_SESSION['products_id']}'");
        }
        else if ($_SESSION['auth'] == 'no_auth')
        {
            $result = mysql_query("SELECT * FROM cart WHERE cart_ip = '99' AND cart_id_product = '{$_SESSION['products_id']}'");         
        }   
    If (mysql_num_rows($result) > 0)
    {
    $row = mysql_fetch_array($result);    
    $new_count = $row["cart_count"] + 1;
        if ($_SESSION['auth'] == 'yes_auth')
        {
        $update = mysql_query ("UPDATE cart SET cart_count='$new_count' WHERE cart_ip = '{$_SESSION['auth_id']}' AND cart_id_product ='{$_SESSION['products_id']}'");   
        }
        else if ($_SESSION['auth'] == 'no_auth')
        {
        $update = mysql_query ("UPDATE cart SET cart_count='$new_count' WHERE cart_ip = '99' AND cart_id_product ='{$_SESSION['products_id']}'");           
        }
    }
    else
    {
        $result = mysql_query("SELECT * FROM table_product WHERE products_id = '{$_SESSION['products_id']}'");
        $row = mysql_fetch_array($result);

            if ($_SESSION['auth'] == 'yes_auth')
            {
                mysql_query("INSERT INTO cart(cart_id_product,cart_price,cart_datetime,cart_ip)
                                VALUES(	
                                    '".$row["products_id_you"]."',
                                    '".$row['new_price']."',					
                                    NOW(),
                                    '".$_SESSION['auth_id']."'                                                                        
                                    ");
            }
            else if ($_SESSION['auth'] == 'no_auth')
            {
                mysql_query("INSERT INTO cart(cart_id_product,cart_price,cart_datetime,cart_ip)
                                VALUES(	
                                    '".$row["products_id_you"]."',
                                    '".$row['new_price']."',					
                                    NOW(),
                                    '99'");  
            }
        }
}
if ($_SESSION['auth'] == 'yes_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE cart.cart_ip = '{$_SESSION['auth_id']}' AND table_product.products_id = cart.cart_id_product");
}
else if ($_SESSION['auth'] == 'no_auth')
{
    $result = mysql_query("SELECT * FROM cart,table_product WHERE cart.cart_ip = '99' AND table_product.products_id = cart.cart_id_product");        
}   
If (mysql_num_rows($result) > 0)
{
$row = mysql_fetch_array($result);

do
{
$count = $count + $row["cart_count"];    
$int = $int + ($row["price"] * $row["cart_count"]); 
}
 while ($row = mysql_fetch_array($result));
 
If ($count == 1 or $count == 21 or $count == 31 or $count == 41 or $count == 51 or $count == 61 or $count == 71 or $count == 81) ( $str = ' �����');
If ($count == 2 or $count == 3 or $count == 4 or $count == 22 or $count == 23 or $count == 24 or $count == 32 or $count == 33 or $count == 34 or $count == 42 or $count == 43 or $count == 44 or $count == 52 or $count == 53 or $count == 54 or $count == 62 or $count == 63 or $count == 64) ( $str = ' ������');
If ($count == 5 or $count == 6 or $count == 7 or $count == 8 or $count == 9 or $count == 10 or $count == 11 or $count == 12 or $count == 13 or $count == 14 or $count == 15 or $count == 16 or $count == 17 or $count == 18 or $count == 19 or $count == 20 or $count == 25 or $count == 26 or $count == 27 or $count == 28 or $count == 29 or $count == 30 or $count == 35 or $count == 36 or $count == 37 or $count == 38 or $count == 39 or $count == 40 or $count == 45 or $count == 46 or $count == 47 or $count == 48 or $count == 49 or $count == 50 or $count == 55 or $count == 56 or $count == 57 or $count == 58 or $count == 59 or $count == 60 or $count == 65) ( $str = ' �������');

if ($count > 81)
{
    $str=" ���";
}
}
else
{

     echo '0';

}
?>