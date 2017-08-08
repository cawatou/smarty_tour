<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.addnewtours.php
 * Type:     function
 * Name:     addnewtours
 * Purpose:  add new tour from the new site
 * -------------------------------------------------------------
 */



function smarty_function_addnewtours($params, &$smarty)
{
  if(!$params['mazafaka']) { $dbHost = "localhost";
       $dbUser = "neoz";
       $dbPass = "J0p9P0n9";
       $dbName = "tours";
   
   
   
       $dbHost1 = "localhost";
       $dbUser1 = "oldtour";
       $dbPass1 = "4L8j4Q0h";
       $dbName1 = "oldtour";
   
   
      
   
   
   
       $link1 = mysqli_connect($dbHost1,$dbUser1,$dbPass1, $dbName1);
   
       
       
   
   
   
       mysqli_set_charset($link1, "utf8");
   
   
       $result1 = mysqli_query($link1,'SELECT city_title FROM  `moihottur__city` WHERE  `city_id` ='.$params['departure']);
   
   
        while ($row=mysqli_fetch_array($result1))
       {
   
   
           $city = $row['city_title'];
   
           
   
          // $touwnFrom = trim($row[17])
       }
   
       mysqli_close($link1);
   
   
   
        $link = mysqli_connect($dbHost,$dbUser,$dbPass, $dbName);
   
   
   
       mysqli_set_charset($link, "utf8");
   
       //$qwer=mysql_query("SELECT UF_MIN_PRICE FROM  `b_uts_iblock_20_section` WHERE  `VALUE_ID` =5292;",$myConnect);
   
      
        $result= mysqli_query($link,'SELECT * FROM  `b_iblock_section` WHERE  `IBLOCK_ID` =20 AND `NAME` LIKE  "%'.$city.'%"' );
   
       while ($row=mysqli_fetch_array($result))
       {
            $imageDir = $imageName = '';
            $imageID = 0;

            $toCountry = explode('(', explode(' - ', $row['NAME'])[1])[0];

            $imageIDRes = mysqli_query($link,'SELECT PREVIEW_PICTURE FROM  `b_iblock_element` WHERE  `IBLOCK_ID` =21 AND `NAME` LIKE  "%'.$toCountry.'%"' );


           while ($imageIDArr=mysqli_fetch_array($imageIDRes)) {
            print_r($imageIDArr);
               $imageID = $imageIDArr['PREVIEW_PICTURE'];
           }

            $imageUrlRes = mysqli_query($link,'SELECT `SUBDIR`,`FILE_NAME` FROM `b_file` WHERE `ID` =  '.$imageID );


           while ($imageUrlArr=mysqli_fetch_array($imageUrlRes)) {
               $imageDir = $imageUrlArr['SUBDIR'];
               $imageName = $imageUrlArr['FILE_NAME'];
           }
   
   
           $priceFromRes = mysqli_query($link,'SELECT UF_MIN_PRICE FROM  `b_uts_iblock_20_section` WHERE  `VALUE_ID` ='.$row['ID'] );
   
           while ($priceFromrow=mysqli_fetch_array($priceFromRes)) {
               $priceFrom = (int) $priceFromrow['UF_MIN_PRICE'];
               $priceFrom = $priceFrom/2;
           }
   
   
           $datesRes = mysqli_query($link,'SELECT NAME FROM  `b_iblock_element` WHERE  `IBLOCK_SECTION_ID` ='.$row['ID'] );
   
   
           $datesArr = array();
           while ($datesrow=mysqli_fetch_array($datesRes)) {
   
               $datesArr[] = explode('_', $datesrow['NAME'])[0];
           }
   
   
   
   
           ?>
   
           <li>
               <a href="http://tour.skipodevelop.com/content/tours/?id=<? echo $row['ID']; ?>" class="tour" onmouseout="this.style.background = '#FFF'" onMouseover="this.style.background = '#FF9'">
                   <span class="tour-country">
                       <?  
   
                          echo $toCountry;



                          

   
                       ?>
                   </span>
   
                   <span class="tour-resort">
                       <?  
   
                          echo explode(' - ', $row['NAME'])[0];
   
                       ?>
                   </span>
   
                   <span class="tour-dates">
                       <? 
   
                               echo $datesArr[0];
                               echo '<br>';
                               echo $datesArr[1];
                           
                        ?>
                   </span>
                   <?
                   
                   if(isset($imageDir) && isset($imageName)) $img_src = 'http://tour.skipodevelop.com/upload/'.$imageDir.'/'.$imageName;
                   if($imageDir == '' && $imageName == '') $img_src = 'http://old.tour.skipodevelop.com/static/thumbs/tours/150x94/znachok-seil.jpg';?>
                   <span class="tour-image fdasgfasgas" style="background-image: url(<?echo $img_src?>); background-size: cover" >
                      
   
                       
   
                       <span class="tour-price">от <? echo $priceFrom; ?> р.</span>
                   </span>
               </a>
           </li>
   
     <?  }
   
   
   
       mysqli_close($link);
   
   

   }
}






     