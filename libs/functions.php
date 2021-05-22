<?php

trait helper
{
        // catch token and personID
    public function GetToken(){
      $username = $this->ReadPropertyString('LoginMailAddress');
      $password = $this->ReadPropertyString('Password');
      $gigya_api = $this->ReadAttributeString('GigyaAPIID');
      $uripersonid = 'https://accounts.eu1.gigya.com/accounts.login';
      $uritokenid = 'https://accounts.eu1.gigya.com/accounts.getJWT';

      $postData = [
        'ApiKey'            => $gigya_api,
        'loginId'           => $username,
        'password'          => $password,
        'include'           => 'data',
        'sessionExpiration' => 86700
      ];

      // get personid and sessioncookie for the token
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uripersonid);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for PersonID: ' . $curl_error, 0);
        return false;
      }
      $responseData = json_decode($response, TRUE);
      
      //print_r($responseData);

      $GetPersonId = $responseData['data']['personId'];
      $this->WriteAttributeString('PersonID', "$GetPersonId");

      $oauth_token = $responseData['sessionInfo']['cookieValue'];
      $postData = [
        'login_token'   => $oauth_token,
        'ApiKey'        => $gigya_api,
        'fields'        => 'data.personId,data.gigyaDataCenter',
        'expiration'    => 86400
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uritokenid);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for TokenID: ' . $curl_error, 0);
        return false;
      }
      $responseData = json_decode($response, TRUE);
      $TokenID = $responseData['id_token'];
      $this->WriteAttributeString('TokenID', "$TokenID");
    }

    public function GetAccountID(){
        
      $kamereon_api = $this->ReadPropertyString('KameronAPIID');
      $country = $this->ReadAttributeString('Country');
      $TokenID_Var = $this->ReadAttributeString('TokenID');
      $PersonID_Var = $this->ReadAttributeString('PersonID');
      $uri = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/persons/'.$PersonID_Var.'?country='.$country;
    
    //Abfrage Kamereon Account ID
      $postData = [
        'apikey: '.$kamereon_api,
        'x-gigya-id_token: '.$TokenID_Var,
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for AccountID: ' . $curl_error, 0);
        return false;
      }
      $responseData = json_decode($response, TRUE);
      $Account = $responseData['accounts'][0]['accountId'];
      $this->WriteAttributeString('AccountID', "$Account");
    }

    public function GetCarInfos(){

      $kamereon_api   = $this->ReadPropertyString('KameronAPIID');
      $country        = $this->ReadAttributeString('Country');

      $AccountID      = $this->ReadAttributeString('AccountID');
      $TokenID_Var    = $this->ReadAttributeString('TokenID');
      $carPicObject   = IPS_GetObjectIDByIdent($this->InstanceID."_CarPic",$this->InstanceID);
      $uri            = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/vehicles?country='.$country;

      $postData = [
          'apikey: '.$kamereon_api,
          'x-gigya-id_token: '.$TokenID_Var,
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for CarInfos: ' . $curl_error, 0);
        return false;
      }
      
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
      $this->WriteAttributeString('VIN', $VIN);

      $CarPic=$responseData['vehicleLinks'][0]['vehicleDetails']['assets'][0]['renditions'][0]['url'];
      $Content=base64_encode(file_get_contents($CarPic));
      //print_r($CarPic);
      //$HTML ='<img src="data:image/png;64,base'.$Content.'"</img>';
      if ($this->ReadPropertyBoolean('CarPicturebool')) 
			{
        IPS_SetMediaContent($carPicObject, $Content);  
      }
    }
       
          //Abfrage Akku-und Ladestatus von Renault
    public function GetBatteryData(){
        
      $TokenID      = $this->ReadAttributeString('TokenID');
      $AccountID    = $this->ReadAttributeString('AccountID');
      $KameronID    = $this->ReadPropertyString('KameronAPIID');
      $VinID        = $this->ReadAttributeString('VIN');
      $CountryID    = $this->ReadAttributeString('Country');
      $uri          = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/kamereon/kca/car-adapter/v2/cars/'.$VinID.'/battery-status?country='.$CountryID;
      
      //if (empty($VinID)){echo "vin fehlt"; ZOE_GetCarInfos(); }
      
      $postData = [
          'apikey: '.$KameronID,
          'x-gigya-id_token: '.$TokenID,
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for Batterydata: ' . $curl_error, 0);
        return false;
      }
      $md5 = md5($response);
      $responseData = json_decode($response, TRUE);
      
      if (@$responseData['error'] == "access_denied") {
        $this->LogMessage("the Kameron API is wrong, please search for a new one", KL_ERROR);
        $Erg['ERRORKAMERON'] = true;
        return $Erg;
        exit;
      }
      $Erg['ERRORKAMERON'] = false
      ;
      if (@$responseData['errors'][0]['errorCode'] == "err.func.wired.unauthorized") {
        $this->LogMessage("the Token ID is expired, lets renew this", KL_WARNING);

        $Erg['ERROR'] = true;
        return $Erg;
        exit;
      }
    // print_r($responseData);
      $Erg['ERROR'] = false;
      foreach ($responseData['data']['attributes'] as $key => $value) {
        $Erg[$key] = $value;
      }
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
        
    public function SetGigyaAPIID(string $value){
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

    function GetCockpitData(){

      $TokenID      = $this->ReadAttributeString('TokenID');
      $AccountID    = $this->ReadAttributeString('AccountID');
      $KameronID    = $this->ReadPropertyString('KameronAPIID');
      $VinID        = $this->ReadAttributeString('VIN');
      $CountryID    = $this->ReadAttributeString('Country');
      $uri          = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/kamereon/kca/car-adapter/v1/cars/'.$VinID.'/cockpit?country='.$CountryID;

      $postData = [
          'apikey: '.$KameronID,
          'x-gigya-id_token: '.$TokenID
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
        $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for CockpitData: ' . $curl_error, 0);
        return false;
      }
      $responseData = json_decode($response, TRUE);
      if (@$responseData['error'] == "access_denied") {
          $Erg['ERROR'] = true;
          return $Erg;
          exit;
      }
      $Erg = $responseData['data']['attributes']['totalMileage'];
      return $Erg;
    }
  
    public function setCarMedia()
    {
      $VARCARPIC=IPS_CreateMedia(1);
      IPS_SetMediaCached($VARCARPIC, true);
      IPS_SetParent($VARCARPIC, $this->InstanceID);
      $ImageFile = IPS_GetKernelDir()."media".DIRECTORY_SEPARATOR."Zoe.jpg";
      IPS_SetMediaFile($VARCARPIC, $ImageFile, False); 
      IPS_SetName($VARCARPIC, $this->Translate('Car picture'));
      IPS_SetIdent($VARCARPIC, $this->InstanceID."_CarPic");
    }

     
    public function startClimate()
    {
      
      $this->SetValue("HVAC", true);
      $TokenID      = $this->ReadAttributeString('TokenID');
      $AccountID    = $this->ReadAttributeString('AccountID');
      $KameronID    = $this->ReadPropertyString('KameronAPIID');
      $VinID        = $this->ReadAttributeString('VIN');
      $CountryID    = $this->ReadAttributeString('Country');
      $uri          = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/kamereon/kca/car-adapter/v1/cars/'.$VinID.'/actions/hvac-start?country='.$CountryID;

      $postData = [
        'Content-type: application/vnd.api+json',
        'apikey: '.$KameronID,
        'x-gigya-id_token: '.$TokenID
      ];

      $jsonData = '{"data":{"type":"HvacStart","attributes":{"action":"start","targetTemperature":"21"}}}';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      ips_sleep(750);
      $this->SetValue("HVAC", false);
      if (empty($response) || $response === false || !empty($curl_error)) {
          $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for starting climate: ' . $curl_error, 0);
          return false;
      }

    }

    private function SetPositionMaps()
    {
      $GoogleApi = $this->ReadPropertyString('GoogleAPIID');

      $HTMLMap =  $this->GetIDForIdent('GoogleMapsHTML');
      $Lat = $this->ReadAttributeFloat('GPSLatitude');
      $Lon = $this->ReadAttributeFloat('GPSLongitude');
		
        // tausche float komma gegen punkt
        $Lat=str_replace(",", ".", $Lat);
        $Lon=str_replace(",", ".", $Lon);
    
        $StringHtml = '
          <html> 
          <head> 
          <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/> 
          <meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
          </head> 
          <iframe src="https://www.google.com/maps/embed/v1/place?q='.$Lat.','.$Lon.'&key='.$GoogleApi.'" title="Hier ist mein Auto"></iframe> 
          
          </body> 
          </html>';
        SetValueString($HTMLMap,$StringHtml);
        
    }

    public function GetPosition()
    {
      $TokenID      = $this->ReadAttributeString('TokenID');
      $AccountID    = $this->ReadAttributeString('AccountID');
      $KameronID    = $this->ReadPropertyString('KameronAPIID');
      $VinID        = $this->ReadAttributeString('VIN');
      $CountryID    = $this->ReadAttributeString('Country');
      $uri          = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$AccountID.'/kamereon/kca/car-adapter/v1/cars/'.$VinID.'/location?country='.$CountryID;

  
      $postData = [
        'apikey: '.$KameronID,
        'x-gigya-id_token: '.$TokenID
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $uri);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
      $response = curl_exec($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
      if (empty($response) || $response === false || !empty($curl_error)) {
          $this->SendDebug(__FUNCTION__, 'Empty answer from Renaultserver for starting climate: ' . $curl_error, 0);
          return false;
      }
      $responseData = json_decode($response, TRUE);

      $Erg["gpsLatitude"]=$responseData['data']['attributes']['gpsLatitude'];
      $this->WriteAttributeFloat('GPSLatitude',$Erg["gpsLatitude"]);

      $Erg["gpsLongitude"]=$responseData['data']['attributes']['gpsLongitude'];
      $this->WriteAttributeFloat('GPSLongitude',$Erg["gpsLongitude"]);

      $Erg["gpsUpdate"]=$responseData['data']['attributes']['lastUpdateTime'];

      return $Erg;
      }
          
}