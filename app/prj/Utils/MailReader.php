<?php
ini_set ('display_errors',1);
error_reporting(E_ALL);
class Utils_MailReader
{
    protected $_offices = array();

    public function __construct()
    {
        require_once 'Zend/Mail.php';
        require_once 'Zend/Mail/Storage/Abstract.php';
        require_once 'Zend/Mail/Storage/Pop3.php';
        require_once 'Zend/Mail/Storage/Imap.php';

        DxFactory::import('DomainObjectModel_Request');
        DxFactory::import('DomainObjectQuery_Office');

        $q = new DomainObjectQuery_Office;

        $offices = $q->findForSletatMail();

        if (empty($offices)) {
            return;
        }

        foreach ($offices as $office) {
            $sletatData = $office->getSletatData();

            if (empty($sletatData['host']['value']) || empty($sletatData['user']['value']) || empty($sletatData['password']['value'])) {
                continue;
            }

            $this->_offices[] = array(
                'office_id'     => $office->getId(),
                'office_title'  => $office->getTitle(),
                'city_id'       => $office->getCityId(),
                'city_title'    => $office->getCity()->getTitle(),
                'host'          => $sletatData['host']['value'],
                'user'          => $sletatData['user']['value'],
                'ssl'           => empty($sletatData['ssl']['value'])           ? 'SSL'          : $sletatData['ssl']['value'],
                'password'      => empty($sletatData['password']['value'])      ? null           : $sletatData['password']['value'],
                'port'          => empty($sletatData['port']['value'])          ? 993            : $sletatData['port']['value'],
                'folder'        => empty($sletatData['folder']['value'])        ? 'INBOX'        : $sletatData['folder']['value'],
                'target_folder' => empty($sletatData['target_folder']['value']) ? 'RA_PROCESSED' : $sletatData['target_folder']['value'],
            );
        }
    }

    public function grab()
    {




        // No offices with prepared data
        if (empty($this->_offices)) {
            return;
        }



		$g_count=0;
        foreach ($this->_offices as $mailOptions) {
           // if ($mailOptions['office_id'] != 94) continue;
            $mail = null;



			$g_count=$g_count+1;

			//throw new Exception(var_dump($mailOptions));

            echo 'Trying to import emails for "'. $mailOptions['office_title'] .' ('. $mailOptions['office_id'] .')"' . "<br>";

            $i = 0;
            $connected = false;

            while (!$connected) {
                $i++;

                try {
                    $mail = new Zend_Mail_Storage_Imap($mailOptions);

                    $connected = true;
                } catch (Zend_Mail_Exception $e) {
                    sleep(1);

                    echo "Error while trying ({$i}) to connect to remote server, skipping...\r\n";
                    echo 'Error: '. $e->getMessage();
                    echo "\r\n\r\n";
                }

                if (!$connected) {
                    $connected = ($i <= 3 ? false : true);
                }
            }

            if (!$connected || !$mail) {
                echo "[!] Connection wasn\'t successful after 3 tries...\r\n\r\n\r\n";

                continue;
            }

            if (!$this->isFolderExists($mail, $mailOptions['target_folder'])) {
                try {
                    $mail->createFolder($mailOptions['target_folder']);
                } catch (Zend_Exception $e) {
                }
            }

            echo $mail->countMessages() . " messages found <br>";

            if($mail->countMessages()==0){
                continue;
            }

            foreach ($mail as $messageId => $message) {
                try {
                    $mail->noop();
                } catch (Zend_Mail_Exception $e) {
                    
                }

                $subject = '';
                $content = strip_tags(base64_decode($message->getContent()));
                $text    = '';


                $subject = $message->subject;
                $subject = str_replace('?= =?utf-8?B?', '', $subject);
                $subject = iconv_mime_decode($subject, 0, 'UTF-8');

                
                $from = $message->from;
                if ($message->hasFlag(Zend_Mail_Storage::FLAG_FLAGGED)) {
                    echo 'Message "'. $subject .'" from "'. $from .'" is "FLAGGED", skipping ('. ($message->getFlags() ? implode(', ', $message->getFlags()) : '---') .')...' . "\r\n";

                    continue;
                }
               
               
                if ($from !== 'noreply@mailserver1.sletat.ru') {
                    
                    echo 'skip'. $from;
                    continue;
                }
                $from = iconv_mime_decode($message->from, 0, 'UTF-8');

                if (!$subject) {
                    echo 'Strange "FROM" string found - "'. $message->subject .'"' . "\r\n";
                }

                if ($message->isMultiPart()) {
                    $part = $message;



                    while ($part->getPart(1)) {
                        if (strtok($part->contentType, ';') == 'text/plain') {
                            $charset = explode('=', $message->contentType);
                            $charset = strtoupper(empty($charset[1]) ? 'utf-8' : $charset[1]);

                            $nextContent = $part->getContent();

                            if ($charset != 'UTF-8') {
                                //$nextContent = mb_convert_encoding($nextContent, 'UTF-8', $charset);
                                $nextContent = iconv($charset, 'UTF-8', $nextContent);
                            }

                            $content .= $nextContent;
                        }
                    }

                    if (base64_decode($content)) {
                        $content = $content;
                    }

                    $text = strip_tags($content);
                } else {
                    if (strtok($message->contentType, ';') == 'text/plain') {
                        $charset = explode('=', $message->contentType);
                        $charset = strtoupper(empty($charset[1]) ? 'utf-8' : $charset[1]);

                        $content = $message->getContent();

                        if (base64_decode($content)) {
                            $content = base64_decode($content);
                        }

                        if ($charset != 'UTF-8') {
                            //$content = mb_convert_encoding($content, 'UTF-8', $charset);
                            $content = iconv($charset, 'UTF-8', $content);
                        }

                        $text = strip_tags($content);
                    }
                }

                try {
                    $mail->noop();
                } catch (Zend_Mail_Exception $e) {
                }

                $subject = trim($subject);
                $content = trim($content);
                $text    = trim($text);

                try {
                    $model = new DomainObjectModel_Request;

                    $model->setType('SLETAT_ORDER');
                    $model->setUserName(empty($subject) ? 'Заказ со sletat.ru' : mb_substr($subject, 0, 255));
                    $model->setUserEmail($from);
                    $model->setUserPhone('+0 000 000 00 00');
                    $model->setUserIp('0.0.0.0');
                    $model->setOfficeId($mailOptions['office_id']);
                    $model->setMessage(empty($text) ? $content : $text);



                    $model->setExtendedData(
                        array(
                            'city_id' => $mailOptions['city_id'],
                            'city'    => $mailOptions['city_title'],
                        )
                    );
                   
                  $model->save();


                } catch (DxException $e) {
                    echo 'Can not save model' . "\r\n";
                }

                $mail->setFlags($messageId, array(Zend_Mail_Storage::FLAG_SEEN, Zend_Mail_Storage::FLAG_FLAGGED));

               // echo 'Email with subject "'. base64_decode($message->getContent()) .'" is prepared for saving...' . "\r\n";
            }

            if (empty($mail)) {
                echo 'No mail adapter were initialized' . "\r\n";

                continue;
            }

            echo 'Saving all prepared entries to database...' . "\r\n";

            echo 'Moving messages to "'. $mailOptions['target_folder'] .'" folder' . "\r\n";

            for ($i = count($mail); $i; --$i) {
                $this->moveMessageToFolder($mail, $i, $mailOptions['target_folder']);
            }

            echo 'Saving was successful!' . "<br>";

			//print $content;
			//if ($g_count==2){break;}

            //ob_flush();



        }
    }

    /**
     * Moved message to remote folder
     *
     * @param Zend_Mail_Storage_Abstract $mail       Zend Mail Storage adapter
     * @param string|int                 $messageId  Unique message ID
     * @param string                     $folderName Target folder name
     * @return null
     */
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

    /**
     * Checks if a folder exists by name
     *
     * @param Zend_Mail_Storage_Imap $mail   Our IMAP object
     * @param string                 $folder The name of the folder to check for
     * @return boolean True if the folder exists, false otherwise.
     *
     * @throws Zend_Mail_Storage_Exception if the current folder cannot be restored.
    */
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