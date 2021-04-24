<?php

trait helper
{
        // catch token and personID
        public function GetToken(){

                $username = $this->ReadPropertyString('LoginMailAddress');
                $password = $this->ReadPropertyString('Password');
                $gigya_api = $this->ReadAttributeString('GigyaAPIID');

                $postData = array(
                'ApiKey' => $gigya_api,
                'loginId' => $username,
                'password' => $password,
                'include' => 'data',
                'sessionExpiration' => 86700
              );
              // get personid and sessioncookie for the token
              $ch = curl_init('https://accounts.eu1.gigya.com/accounts.login');
              curl_setopt($ch, CURLOPT_POST, TRUE);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

              $response = curl_exec($ch);
              if ($response === FALSE) die(curl_error($ch));  
              $responseData = json_decode($response, TRUE);
              
              //print_r($responseData);

              $GetPersonId = $responseData['data']['personId'];
              $this->UpdateFormField('PersonID', 'value', $GetPersonId);

              $oauth_token = $responseData['sessionInfo']['cookieValue'];
              // use sessioncookie to get new token every x hour
              $postData = array(
                'login_token' => $oauth_token,
                'ApiKey' => $gigya_api,
                'fields' => 'data.personId,data.gigyaDataCenter',
                'expiration' => 86400
              );
              $ch = curl_init('https://accounts.eu1.gigya.com/accounts.getJWT');
              curl_setopt($ch, CURLOPT_POST, TRUE);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
              $response = curl_exec($ch);
              if ($response === FALSE) die(curl_error($ch));
              $responseData = json_decode($response, TRUE);
              $TokenID = $responseData['id_token'];
              $this->UpdateFormField('TokenID', 'value', $TokenID);
            }

        public function GetAccountID(){
            
          $kamereon_api = $this->ReadPropertyString('KameronAPIID');
          $country = $this->ReadAttributeString('Country');
          $TokenID_Var = $this->ReadPropertyString('TokenID');
          $PersonID_Var = $this->ReadPropertyString('PersonID');
        
        //Abfrage Kamereon Account ID
          $postData = array(
            'apikey: '.$kamereon_api,
            'x-gigya-id_token: '.$TokenID_Var,
          );
          $ch = curl_init('https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/persons/'.$PersonID_Var.'?country='.$country);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
          $response = curl_exec($ch);
          if ($response === FALSE) die(curl_error($ch));
          $responseData = json_decode($response, TRUE);
          //print_r($responseData);
          $Account = $responseData['accounts'][0]['accountId'];
          $this->UpdateFormField('AccountID', 'value', $Account);        
        }

        public function GetCarInfos(){

            $kamereon_api = $this->ReadPropertyString('KameronAPIID');
            $country = $this->ReadAttributeString('Country');

            $AccountID = $this->ReadPropertyString('AccountID');
            $TokenID_Var = $this->ReadPropertyString('TokenID');
 
            $postData = array(
                'apikey: '.$kamereon_api,
                'x-gigya-id_token: '.$TokenID_Var,
            );
            $ch = curl_init('https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/vehicles?country='.$country);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
            $response = curl_exec($ch);
            if ($response === FALSE) die(curl_error($ch));
            $responseData = json_decode($response, TRUE);
            if (@$responseData['error'] == "access_denied") {
              $this->LogMessage("the Kameron API is wrong, please search for a new one", KL_ERROR);
              $Erg['ERRORKAMERON'] = true;
              return $Erg;
              exit;
            }
            $Erg['ERRORKAMERON'] = false;
            if (@$responseData['errors'][0]['errorCode'] == "err.func.wired.unauthorized") {
              $this->LogMessage("the Token ID is expired, lets renew this", KL_WARNING);
              $Erg['ERROR'] = true;
              return $Erg;
              exit;
            }
            $Erg['ERROR'] = false;
            //print_r($responseData);
            $VIN=$responseData['vehicleLinks'][0]['vin'];
            $this->UpdateFormField('VIN', 'value', $VIN); 

            $CarPic=$responseData['vehicleLinks'][0]['vehicleDetails']['assets'][0]['renditions'][0]['url'];
            $Content=base64_encode(file_get_contents($CarPic));
            //print_r($CarPic);
            $HTML ='<img src="data:image/png;64,base'.$Content.'"</img>';
            $this->UpdateFormField('CarPicture', 'value', $HTML); 
            }
       
          //Abfrage Akku-und Ladestatus von Renault
          public function GetBatteryData(){
           
            $TokenID =      $this->ReadPropertyString('TokenID');
            $AccountID =    $this->ReadPropertyString('AccountID');
            $KameronID =    $this->ReadPropertyString('KameronAPIID');
            $VinID =        $this->ReadPropertyString('VIN');
            $CountryID =    $this->ReadAttributeString('Country');

            $postData = array(
                'apikey: '.$KameronID,
                'x-gigya-id_token: '.$TokenID
            );
            $ch = curl_init('https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/kamereon/kca/car-adapter/v2/cars/'.$VinID.'/battery-status?country='.$CountryID);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
            $response = curl_exec($ch);
            if ($response === FALSE) die(curl_error($ch));
            $md5 = md5($response);
            $responseData = json_decode($response, TRUE);
            if (@$responseData['error'] == "access_denied") {
              $this->LogMessage("the Kameron API is wrong, please search for a new one", KL_ERROR);
              $Erg['ERRORKAMERON'] = true;
              return $Erg;
              exit;
            }
            $Erg['ERRORKAMERON'] = false;
            if (@$responseData['errors'][0]['errorCode'] == "err.func.wired.unauthorized") {
              $this->LogMessage("the Token ID is expired, lets renew this", KL_WARNING);

              $Erg['ERROR'] = true;
              return $Erg;
              exit;
            }
            //print_r($responseData);
            $Erg['ERROR'] = false;
            foreach ($responseData['data']['attributes'] as $key => $value) {
              $Erg[$key] = $value;
            }
           // $this->MaintainVariable("PlugStatus2", "Status des Device", 0, "~Switch" , 0, $this->ReadPropertyBool("PlugStatusbool") == 1);
           // echo $Erg["plugStatus"];
           /*
              (
                [timestamp] => 2021-04-17T16:12:57Z
                [batteryLevel] => 67
                [batteryTemperature] => 20
                [batteryAutonomy] => 158
                [batteryCapacity] => 0
                [batteryAvailableEnergy] => 31
                [plugStatus] => 0
                [chargingStatus] => 0
                [chargingRemainingTime] => 100
                [chargingInstantaneousPower] => 11,1
              )
            */
            //print_r($Erg);
            return $Erg;
          }               
        
        public function SetGigyaAPIID($value)
        {
          $this->WriteAttributeString('Country',$value);

          if ($this->ReadAttributeString('Country') == "DE")
          {
            $this->WriteAttributeString('GigyaAPIID', "3_7PLksOyBRkHv126x5WhHb-5pqC1qFR8pQjxSeLB6nhAnPERTUlwnYoznHSxwX668");
          }
          
          if ($this->ReadAttributeString('Country') == "AT")
          {		
            $this->WriteAttributeString('GigyaAPIID', "3__B4KghyeUb0GlpU62ZXKrjSfb7CPzwBS368wioftJUL5qXE0Z_sSy0rX69klXuHy");
          }

          if ($this->ReadAttributeString('Country') == "SE")
          {		
            $this->WriteAttributeString('GigyaAPIID', "3_EN5Hcnwanu9_Dqot1v1Aky1YelT5QqG4TxveO0EgKFWZYu03WkeB9FKuKKIWUXIS");
          }

          if ($this->ReadAttributeString('Country') == "GB")
          {		
            $this->WriteAttributeString('GigyaAPIID', "3_e8d4g4SE_Fo8ahyHwwP7ohLGZ79HKNN2T8NjQqoNnk6Epj6ilyYwKdHUyCw3wuxz");
          }
        }

        public function TST()
            {
              $BLA = $this->ReadPropertyString('AccountID');
              $this->UpdateFormField('AccountIDAction', 'value', $BLA);

              $BLA2 = $this->ReadPropertyString('PersonID');
              $this->UpdateFormField('PersonIDAction', 'value', $BLA2);
              
              $BLA3 = $this->ReadPropertyString('TokenID');
              $this->UpdateFormField('TokenIDAction', 'value', $BLA3);
              
              $BLA4 = $this->ReadPropertyString('VIN');
              $this->UpdateFormField('VINAction', 'value', $BLA4);
              
              $BLA5 = $this->ReadAttributeString('GigyaAPIID');
              $this->UpdateFormField('GigyaAPIIDAction', 'value', $BLA5);

              //echo $BLA5;
            }
        
}