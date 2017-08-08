<?php
class Utils_MSKReader
{

    public function __construct()
    {
        require_once 'Zend/Mail.php';
        require_once 'Zend/Mail/Storage/Abstract.php';
        require_once 'Zend/Mail/Storage/Pop3.php';
        require_once 'Zend/Mail/Storage/Imap.php';
		
		DxFactory::import('DomainObjectModel_Request');
  
    }

    public function grab()
    {
		$connected = false;

		while (!$connected) {
                $i++;
				
				
                try {
                    $mail = new Zend_Mail_Storage_Imap(array(
						'host'     => 'imap.yandex.ru',
						'user'     => 'on-line@moihottur.ru',
						'password' => 'N275vkXXF0',
						'port' => '993',
						'ssl' => 'SSL',
						));
					echo 'Соединение установлено <br>';
                    $connected = true;
                } catch (Zend_Mail_Exception $e) {
                    sleep(1);

                    echo "Ошибка попытка  №({$i}) подколючения к серверу почты, ожидание 1 секунда...<br>";
                    echo 'Ошибка: '. $e->getMessage();
                    echo "<br>";
                }

                if (!$connected) {
                    $connected = ($i <= 3 ? false : true);
                }
				
				if (!$connected || !$mail) {
					echo "[!] Подключение не было успешным после 3-х попыток...<br><br>";

					continue;
				}
				
				
				if (!$this->isFolderExists($mail, 'orders')) {
					try {
						$mail->createFolder('orders');
						echo 'Создаём папку orders<br>';
					} catch (Zend_Exception $e) {
					}
				}

				if (!$this->isFolderExists($mail, 'new_read')) {
					try {
						$mail->createFolder('new_read');
						echo 'Создаём папку new_read<br>';
					} catch (Zend_Exception $e) {
					}
				}

		
				
				if($mail->countMessages()==0){
					echo 'Входящих сообщений не найдено.<br>';
					continue;
				} else{
					echo $mail->countMessages() . " сообщений найдено. <br>";
				}
			
				foreach ($mail as $messageId => $message) {
					
					    
						
						if (base64_decode($message->getContent())) {
                            $content = base64_decode($message->getContent());
                        }
						
						$text = explode("заказ!", $content);
						
						$text = explode("Пройдите", $text[1]);
						$text = $text[0]; 
						$text = strip_tags($text,'<nobr>');
						$text = str_replace("&nbsp;"," ",$text);
						$text = str_replace("<nobr>","",$text);
						$text="Заказ №".$text;
						$text = str_replace("</nobr>","\n",$text);
						//echo strip_tags($content);
						
					
						//throw new Exception(var_dump('a'));
						if (strpos($message->from, 'SLETAT.RU' )){
							
							if (substr_count($message->subject, 'Оповещение о новом предзаказе')>0){
								//echo $message->subject.'<br>';
								try {
									$model = new DomainObjectModel_Request;

									$model->setType('SLETAT_ONLINE');
									$model->setUserName(empty($message->subject) ? 'Заказ со sletat.ru' : mb_substr($message->subject, 0, 255));
									$model->setUserEmail('noreply@sletat.ru');
									$model->setUserPhone('+0 000 000 00 00');
									$model->setUserIp('0.0.0.0');
									$model->setOfficeId(80);
									$model->setMessage($text);



									$model->setExtendedData(
										array(
											'city_id' => 10,
											'city'    => 'Москва',
										)
									);

									$model->save();
									
								} catch (DxException $e) {
									echo 'Can not save model' . "\r\n";
								}
							}
								
							//echo $message->from.'<br>';
							for ($i = count($mail); $i; --$i) {
								$this->moveMessageToFolder($mail, $i, 'orders');
							
							}
							//$this->moveMessageToFolder($mail, $i, 'orders');
							//$mail->setFlags($messageId, array(Zend_Mail_Storage::FLAG_SEEN, Zend_Mail_Storage::FLAG_FLAGGED));
						} else {
							//$mail->setFlags($messageId, array(Zend_Mail_Storage::FLAG_SEEN, Zend_Mail_Storage::FLAG_FLAGGED));
							//echo'левое письмо<br>';
							
							//$this->moveMessageToFolder($mail, $i, 'read');
						}						
						
					 
					

					
					
				}
				
				
				
				
        }
		
	  
        // No offices with prepared data
       
    }
	
	public function moveMessageToFolder(Zend_Mail_Storage_Abstract $mail, $messageId, $folderName = 'RA_PROCESSED')
    {
        try {
            $messageUniqueId = $mail->getUniqueId($messageId);
            $messageNumber   = $mail->getNumberByUniqueId($messageUniqueId);

            $mail->moveMessage($messageNumber, $folderName);
        } catch (Zend_Exception $e) {
            echo 'Exception caught while trying to move message to folder. Message - '. $e->getMessage();
        }
    }


    public function isFolderExists(Zend_Mail_Storage_Imap $mail, $folder) {
       $result = true;

       $oldFolder = $mail->getCurrentFolder();

       try {
           $mail->selectFolder($folder);
       } catch (Zend_Mail_Storage_Exception $e) {
           $result = false;
       }

       $mail->selectFolder($oldFolder);

       return $result;
   }

}