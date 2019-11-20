<?php
    header('Access-Control-Allow-Origin: *');
    $logfile = 'requests-test.log';

    $shipping = json_decode($_REQUEST['shipping']);
    $order = $_REQUEST['order'];
    $order = preg_replace('/<span[^>]*>(.*)<\/span>/Ui', '\\1', $order);
    $summ = $_REQUEST['summ'];
    $status = $_REQUEST['status'];
    if($status == 1) { 
        $status_message = 'Заказ не оплачен'; 
    } else if($status == 2) { 
        $status_message = 'Заказ оплачен картой'; 
    } else if($status == 3) { 
        $status_message = 'Заказ оплачен через G Pay'; 
    }
    $to = 'sergey59rg@live.com, '.$shipping->{'email'}.'';
    $subject = 'Заказ с мобильного приложения';
    $message_array = 'Заказ номер: '.$shipping->{'order_id'}."\r\n".
    'Статус: '.$status."\r\n".
    'Имя: '.$shipping->{'name'}."\r\n".
    'На кого оформлен договор: '.$shipping->{'contract'}."\r\n".
    'email: '.$shipping->{'email'}."\r\n".
    'Телефон: '.$shipping->{'phone'}."\r\n".
    'Дата доставки(получения): '.date('Y-m-d', strtotime($shipping->{'date'}))."\r\n".
    /*'Время доставки(получения): '.date('H:i:s', strtotime($shipping->{'time'}))."\r\n".*/
    'Товары: '.$order.
    'Сумма заказа: '.$summ."\r\n".
    'Время заказа: '.date("Y-m-d H:i:s")."\r\n".
    'Адрес: '.$shipping->{'address'}."\r\n".
    'Комментарий: '.$shipping->{'review'}."\r\n";

    $headers = 'From: vodovoz@qsolution.ru'."\r\n".
               'Content-type: text/plain; charset=WINDOWS-1251'."\r\n".
               'Reply-To: '.$shipping->{'email'}. "\r\n" .
               'X-Mailer: PHP/' . phpversion();

$date = date("H:i:s d.m.Y");        //дата события

file_put_contents($logfile, $date."|".$headers."|".$message_array."|\r\n", FILE_APPEND);

    if($to) {
        if (mail($to, conv($subject), conv($message_array), $headers)) {
            echo json_encode(array("status"=>"ok"));
        } else {
            echo json_encode(array("status"=>"error"));
        }
    }

    function conv($text){
    	return iconv("UTF-8" , "WINDOWS-1251",  $text);
    }

?>
