<?php
    header('Access-Control-Allow-Origin: *');
    $shipping = json_decode($_REQUEST['shipping']);
    $order = $_REQUEST['order'];
    $order = preg_replace('/<span[^>]*>(.*)<\/span>/Ui', '\\1', $order);
    $summ = $_REQUEST['summ'];
    //$to = 'zyk-sergej@yandex.ru';
    //$to = 'vodovoz-spb@yandex.ru';
    $to = 'zakaz@vodovoz-spb.ru';
    $subject = 'Заказ с мобильного приложения';
    $message_array = 'Имя: '.$shipping->{'name'}."\r\n".
    'На кого оформлен договор: '.$shipping->{'contract'}."\r\n".
    'email: '.$shipping->{'email'}."\r\n".
    'Телефон: '.$shipping->{'phone'}."\r\n".
    'Дата доставки(получения): '.date('Y-m-d', strtotime($shipping->{'date'}))."\r\n".
    /*'Время доставки(получения): '.date('H:i:s', strtotime($shipping->{'time'}))."\r\n".*/
    'Товары: '.$order.
    'Стоимость: '.$summ."\r\n".
    'Скидка 5%'."\r\n".
    'Сумма заказа: '.round($summ * .95)."\r\n".
    'Время заказа: '.date("Y-m-d H:i:s")."\r\n".
    'Адрес: '.$shipping->{'address'}."\r\n".
    'Комментарий: '.$shipping->{'review'}."\r\n";

    $headers = 'From: '.$shipping->{'email'}. "\r\n" .
               'Content-type: text/plain; charset=windows-1251'."\r\n".
               'Reply-To: '.$shipping->{'email'}. "\r\n" .
               'X-Mailer: PHP/' . phpversion();
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