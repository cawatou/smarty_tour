<?
if((isset($_POST['name'])&&$_POST['name']!="")&&(isset($_POST['phone'])&&$_POST['phone']!="")){ //Проверка отправилось ли наше поля name и не пустые ли они
        $to = 'franchise@moihottur.ru'; //Почта получателя, через запятую можно указать сколько угодно адресов
        $subject = 'Заявка франчайзинг'; //Загаловок сообщения
        $message = '
                <html>
                    <head>
                        <title>'.$subject.'</title>
                    </head>
                    <body>
                        <p>Имя: '.$_POST['name'].'</p>
						<p>Город: '.$_POST['city'].'</p>
						<p>Email: '.$_POST['email'].'</p>
                        <p>Телефон: '.$_POST['phone'].'</p>
						<p>Тип агенаства: '.$_POST['agency'].'</p>                         
                    </body>
                </html>'; //Текст нащего сообщения можно использовать HTML теги
        $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
        $headers .= "From: Франчайзинг заявка <info@moihottur.ru>\r\n"; //Наименование и почта отправителя
        mail($to, $subject, $message, $headers); //Отправка письма с помощью функции mail
}

?>
