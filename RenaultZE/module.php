<?php

declare(strict_types=1);
require_once __DIR__ . '/../libs/functions.php';

	class RenaultZE extends IPSModule
	{
		use Helper;
		public function Create()
		{
			//Never delete this line!
			parent::Create();
		
			$this->RegisterPropertyString('LoginMailAddress', '');
			$this->RegisterPropertyString('Password', '');

			$this->RegisterPropertyString('PhaseVersion', 'none');
			$this->RegisterAttributeString('Country', 'DE');
			
			$this->RegisterAttributeString('GigyaAPIID', '3_7PLksOyBRkHv126x5WhHb-5pqC1qFR8pQjxSeLB6nhAnPERTUlwnYoznHSxwX668');

			$this->RegisterPropertyString('KameronAPIID', 'YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J');
			
			$this->RegisterAttributeString("TokenID", '');
			$this->RegisterAttributeString('PersonID', '');
			$this->RegisterAttributeString("AccountID", '');

			//$this->RegisterAttributeString('CarPicture', '');
			$this->RegisterPropertyBoolean('CarPicturebool', false);

			$this->RegisterAttributeString('VIN', '');
			$this->RegisterPropertyBoolean('VINbool', false);

			$this->RegisterPropertyInteger('BatteryLevel', 0);
			$this->RegisterPropertyBoolean('BatteryLevelbool', false);

			$this->RegisterPropertyInteger('BatteryTemperature', 0);
			$this->RegisterPropertyBoolean('BatteryTemperaturebool', false);
			
			$this->RegisterPropertyInteger('BatteryCapacity', 0);
			$this->RegisterPropertyBoolean('BatteryCapacitybool', false);

			$this->RegisterPropertyInteger('BatteryAutonomy', 0);
			$this->RegisterPropertyBoolean('BatteryAutonomybool', false);
			
			$this->RegisterPropertyInteger('BatteryAvailableEnergy', 0);
			$this->RegisterPropertyBoolean('BatteryAvailableEnergybool', false);

			$this->RegisterPropertyInteger('PlugStatus', 0);
			$this->RegisterPropertyBoolean('PlugStatusbool', false);

			$this->RegisterPropertyFloat('ChargingStatus', 0);
			$this->RegisterPropertyBoolean('ChargingStatusbool', false);

			$this->RegisterPropertyInteger('ChargingRemainingTime', 0);
			$this->RegisterPropertyBoolean('ChargingRemainingTimebool', false);

			$this->RegisterPropertyInteger('LastChangeBattery', 0);
			$this->RegisterPropertyBoolean('LastChangeBatterybool', false);

			$this->RegisterPropertyInteger('ActualKM', 0);
			$this->RegisterPropertyBoolean('ActualKMbool', false);

			$this->RegisterPropertyBoolean('HVACbool', false);

			$this->RegisterPropertyBoolean('GPSLatitudeBool', false);
			$this->RegisterAttributeFloat('GPSLatitude', 0);

			$this->RegisterPropertyBoolean('GPSLongitudeBool', false);
			$this->RegisterAttributeFloat('GPSLongitude', 0);

			$this->RegisterPropertyBoolean('GPSUpdateBool', false);	

			$this->RegisterPropertyBoolean('GoogleMapsBool', false);

			$this->RegisterPropertyString('GoogleAPIID','');

			$this->RegisterAttributeBoolean('FirstRunDone', false);

			//$this->RegisterTimer('RZE_UpdateData', 0, 'RZE_UpdateData('.$this->InstanceID.');');

			$this->RegisterTimer('RZE_UpdateData', 0, 'RZE_UpdateData($_IPS[\'TARGET\']);');
			$this->RegisterPropertyInteger('UpdateDataInterval', 10);
			
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
			//$this->UnregisterTimer('RZE_UpdateData');

		}

		public function ApplyChanges()
		{
	
			//Never delete this line!
			parent::ApplyChanges();
					
			if ($this->ReadPropertyBoolean('BatteryLevelbool')) 
			{
				if (!@$this->GetIDForIdent('BatteryLevel')) 
				{
					$this->RegisterVariableInteger('BatteryLevel', $this->Translate('Battery Level'), "~Battery.100");

				}
			} 
			else 
				{
					$this->UnregisterVariable("BatteryLevel");
				}

			if ($this->ReadPropertyBoolean('BatteryTemperaturebool')) 
			{
				if (!@$this->GetIDForIdent('BatteryTemperature')) 
				{
					$this->RegisterVariableInteger('BatteryTemperature', $this->Translate('Battery Temperature'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("BatteryTemperature");
				}
		
			if ($this->ReadPropertyBoolean('BatteryCapacitybool')) 
			{
				if (!@$this->GetIDForIdent('BatteryCapacity')) 
				{
					$this->RegisterVariableInteger('BatteryCapacity', $this->Translate('Battery Capacity'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("BatteryCapacity");
				}
				
			if ($this->ReadPropertyBoolean('BatteryAutonomybool')) 
			{
				if (!@$this->GetIDForIdent('BatteryAutonomy')) 
				{
					$this->RegisterVariableInteger('BatteryAutonomy', $this->Translate('Battery Autonomy'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("BatteryAutonomy");
				}

			if ($this->ReadPropertyBoolean('BatteryAvailableEnergybool')) 
			{
				if (!@$this->GetIDForIdent('BatteryAvailableEnergy')) 
				{
					$this->RegisterVariableInteger('BatteryAvailableEnergy', $this->Translate('Battery Available Energy'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("BatteryAvailableEnergy");
				}
			
			if ($this->ReadPropertyBoolean('PlugStatusbool')) 
			{
				if (!@$this->GetIDForIdent('PlugStatus')) 
				{
					if (!@IPS_VariableProfileExists("RZE_PlugStatus"))
					{
						IPS_CreateVariableProfile("RZE_PlugStatus", 1);
						IPS_SetVariableProfileAssociation("RZE_PlugStatus", 0, $this->Translate('not plugged in'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_PlugStatus", 1, $this->Translate('plugged in'), "", 0xFFFFFF);
					}
					$this->RegisterVariableInteger('PlugStatus', $this->Translate('Plug-Status'),"RZE_PlugStatus");
				}
			} 
			else 
				{
					$this->UnregisterVariable("PlugStatus");
				}

			if ($this->ReadPropertyBoolean('ChargingStatusbool')) 
			{
				if (!@$this->GetIDForIdent('ChargingStatus')) 
				{
					if (!@IPS_VariableProfileExists("RZE_ChargingStatus"))
					{
						IPS_CreateVariableProfile("RZE_ChargingStatus", 2);
						IPS_SetVariableProfileDigits("RZE_ChargingStatus", 1);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 0.1, $this->Translate('Waiting for planned charge'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 0.2, $this->Translate('Charge ended'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 0.3, $this->Translate('Waiting for current charge'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 0.4, $this->Translate('Energy flap opened'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 0, $this->Translate('Not charging or plugged in'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", 1.0, $this->Translate('Charging'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", -1.0, $this->Translate('Error while loading'), "", 0xFFFFFF);
						IPS_SetVariableProfileAssociation("RZE_ChargingStatus", -1.1, $this->Translate('not available'), "", 0xFFFFFF);
						IPS_SetVariableProfileValues("RZE_ChargingStatus", -2, 100, 0.1);
					}
					$this->RegisterVariableFloat('ChargingStatus', $this->Translate('Charging Status'),"RZE_ChargingStatus");
				}
			} 
			else 
				{
					$this->UnregisterVariable("ChargingStatus");
					if (@IPS_VariableProfileExists("RZE_ChargingStatus"))
					{
						IPS_DeleteVariableProfile("RZE_ChargingStatus");
					}
				}

			if ($this->ReadPropertyBoolean('ChargingRemainingTimebool')) 
			{
				if (!@$this->GetIDForIdent('ChargingRemainingTime')) 
				{
					$this->RegisterVariableInteger('ChargingRemainingTime', $this->Translate('Charging Remaining Time'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("ChargingRemainingTime");
				}

			if ($this->ReadPropertyBoolean('LastChangeBatterybool')) 
			{
				if (!@$this->GetIDForIdent('LastChangeBattery')) 
				{
					$this->RegisterVariableInteger('LastChangeBattery', $this->Translate('last changed Batterystatus'), "~UnixTimestamp");
				}
			} 
			else 
				{
					$this->UnregisterVariable("LastChangeBattery");
				}
			
			if ($this->ReadPropertyBoolean('ActualKMbool')) 
			{
				if (!@$this->GetIDForIdent('ActualKM')) 
				{
					$this->RegisterVariableInteger('ActualKM', $this->Translate('actual-KM'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("ActualKM");
				}


			if ($this->ReadPropertyBoolean('VINbool')) 
			{
				if (!@$this->GetIDForIdent('VIN')) 
				{
					$this->RegisterVariableString('VIN', $this->Translate('Vehicle Identification number'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("VIN");
				}

			if ($this->ReadPropertyBoolean('HVACbool')) 
			{
				if (!@$this->GetIDForIdent('HVAC')) 
				{
					$this->RegisterVariableBoolean('HVAC', $this->Translate('Climate Control'), "~Switch");
					$this->EnableAction("HVAC");
				}
			} 
			else 
				{
					if (@$this->GetIDForIdent('HVAC')) 
					{
						$this->DisableAction("HVAC");
					}
					$this->UnregisterVariable("HVAC");
				}

			if ($this->ReadPropertyBoolean('CarPicturebool')) 
			{
				if (!@$this->GetIDForIdent($this->InstanceID.'_CarPic'))
				{
					//$this->RegisterVariableString('CarPicture', $this->Translate('CarPicture'), "~HTMLBox");
					//$this->SetValue("CarPicture", $this->ReadAttributeString('CarPicture'));
					$this->setCarMedia();

					if ($this->ReadAttributeString('AccountID')){
						$this->GetCarInfos();
					}
				}
			} 
			else 
				{
					if (@$this->GetIDForIdent($this->InstanceID.'_CarPic'))
					{
						IPS_DeleteMedia(IPS_GetObjectIDByIdent($this->InstanceID."_CarPic",$this->InstanceID), true);
					}
				}	

				if ($this->ReadPropertyBoolean('GPSLatitudeBool')) 
				{
					if (!@$this->GetIDForIdent('GPSLatitude')) 
					{
						$this->RegisterVariableFloat('GPSLatitude', "GPS Latitude");
					}
				} 
				else 
					{
						$this->UnregisterVariable("GPSLatitude");
					}
	
				if ($this->ReadPropertyBoolean('GPSLongitudeBool')) 
				{
					if (!@$this->GetIDForIdent('GPSLongitude')) 
					{
						$this->RegisterVariableFloat('GPSLongitude', "GPS Longitude");
					}
				} 
				else 
					{
						$this->UnregisterVariable("GPSLongitude");
					}

				if ($this->ReadPropertyBoolean('GPSUpdateBool')) 
				{
					if (!@$this->GetIDForIdent('GPSUpdate')) 
					{
						$this->RegisterVariableInteger('GPSUpdate', $this->Translate('last GPS Update'), "~UnixTimestamp");
					}
				} 
				else 
					{
						$this->UnregisterVariable("GPSUpdate");
					}

				if ($this->ReadPropertyBoolean('GoogleMapsBool')) 
				{
					if (!@$this->GetIDForIdent('GoogleMapsHTML')) 
					{
						$this->RegisterVariableString('GoogleMapsHTML', "Google Maps", "~HTMLBox");

						if ($this->ReadAttributeString('AccountID')){
							$this->GetPosition();
						}
						$this->SetPositionMaps();
						echo $this->ReadPropertyString('GoogleAPIID');
					}
				} 
				else 
					{
						$this->UnregisterVariable("GoogleMapsHTML");
					}

				
				if (!@$this->GetStatus() == 104) 
				{
					$this->SetGigyaAPIID($this->ReadAttributeString('Country'));
					if ($this->ReadAttributeString('PersonID')){
						$this->UpdateData();
					}
				}
				$this->SetTimerInterval('RZE_UpdateData', $this->ReadPropertyInteger('UpdateDataInterval') * 60 * 1000);

				if ($this->ReadPropertyInteger('UpdateDataInterval') == 0){
					$this->SetStatus(104);
				} else {
					$this->SetStatus(102);
				}
			
		}

		public function GetConfigurationForm()
		{
			$jsonForm = json_decode(file_get_contents(__DIR__ . "/form.json"), true);


			if ($this->ReadPropertyString('PhaseVersion') == "Phase_2")
			{
				$jsonForm["elements"][5]["items"][1]["visible"] = false; // hide Battery Temperature 
				$jsonForm["elements"][5]["items"][2]["visible"] = true; // show Battery Capacity
				
			}
			if ($this->ReadPropertyString('PhaseVersion') == "Phase_1")
			{
				$jsonForm["elements"][5]["items"][1]["visible"] = true; // show Battery Temperature 
				$jsonForm["elements"][5]["items"][2]["visible"] = false; // hide Battery Capacity
			}

			$jsonForm["elements"][4]["value"] = $this->ReadAttributeString('Country');
			$jsonForm["elements"][6]["items"][1]["value"] = $this->ReadAttributeString('GigyaAPIID');
			$jsonForm["elements"][6]["items"][1]["visible"] = false;
			
			$jsonForm["elements"][7]["items"][6]["visible"] = $this->ReadPropertyBoolean('GoogleMapsBool');
		
			if ($this->Getstatus() == 104 ){
				$jsonForm["elements"][8]["visible"] = true;
			}
			if ($this->ReadAttributeString('PersonID')){
				$jsonForm["actions"][0]["visible"] = false;
				$jsonForm["actions"][1]["visible"] = false;
				$jsonForm["actions"][3]["visible"] = true;
				$jsonForm["actions"][4]["visible"] = true;
				$jsonForm["actions"][5]["visible"] = true;
			}

			if ($this->ReadAttributeBoolean('FirstRunDone') ) // after First run is done, show the expansion panels
			{
				$jsonForm["elements"][5]["visible"] = true; // show Variable activation Panel
				$jsonForm["elements"][6]["visible"] = true; // show more options
				if ($this->ReadPropertyString('PhaseVersion') == "Phase_2")
				{
					$jsonForm["elements"][7]["visible"] = true; // show GPS Data
				}
			}

			return json_encode($jsonForm);
		}

		public function FirstRun()
		{
			// after you entered all data into the form and apply your changes, you need to click the first run button. 
			// so we run to get the token, personalid, accountid, Carinfos like VIN and Carpictures. After all we update first time and show some buttons.
			
			$this->UpdateFormField("FirstRunProgress", "visible", true);
  			$this->GetToken();
			$this->GetAccountID();
			$this->GetCarInfos();
			$this->UpdateData();
			$this->UpdateFormField("FirstRunProgress", "visible", false);
			$this->WriteAttributeBoolean('FirstRunDone', true);
			$this->ReloadForm();
		}
	
		public function UpdateToken(){
			$this->GetToken();
		}

		public function UpdateData()
		{
			$BatteryData = $this->GetBatteryData();
			if ( $BatteryData['ERRORKAMERON'] == true) {
				$this->LogMessage($this->Translate('can not update Data, please check Kameron API ID'), KL_ERROR);
				exit;	
			 };
			if ( $BatteryData['ERROR'] == true) {
				$this->LogMessage($this->Translate('i have a problem to update data, renew token.'), KL_WARNING);
				$this->GetToken();
				$BatteryData = $this->GetBatteryData();
			 };
			 if ( $BatteryData['ERROR'] == true) {
				$this->LogMessage($this->Translate('update not possible, renew token failed.'), KL_WARNING);
				exit;
			 };
           
			if (@$this->GetIDForIdent('BatteryLevel')) 
			{
				$this->SetValue("BatteryLevel", $BatteryData['batteryLevel']);
			}
			if (@$this->GetIDForIdent('BatteryTemperature')) 
			{
				$this->SetValue("BatteryTemperature", $BatteryData['batteryTemperature']);
			}
			if (@$this->GetIDForIdent('BatteryAutonomy')) 
			{
				$this->SetValue("BatteryAutonomy", $BatteryData['batteryAutonomy']);
			}
			if (@$this->GetIDForIdent('BatteryCapacity')) 
			{
				if (isset($BatteryData['batteryCapacity']))
				{
					$this->SetValue("BatteryCapacity", $BatteryData['batteryCapacity']);
				}
				else
				{
					$this->LogMessage($this->Translate('update not possible, data not available'). " batteryCapacity", KL_NOTIFY);
				}
			}
			if (@$this->GetIDForIdent('PlugStatus')) 
			{
				if (isset($BatteryData['plugStatus']))
				{
					$this->SetValue("PlugStatus", $BatteryData['plugStatus']);
				}
				else
				{
					$this->LogMessage($this->Translate('update not possible, data not available'). " plugStatus", KL_NOTIFY);
				}
			}
			if (@$this->GetIDForIdent('ChargingStatus')) 
			{
				$this->SetValue("ChargingStatus", $BatteryData['chargingStatus']);
			}
			if (@$this->GetIDForIdent('ChargingRemainingTime')) 
			{
				$this->SetValue("ChargingRemainingTime", $BatteryData['chargingRemainingTime']);
			}
			if (@$this->GetIDForIdent('BatteryAvailableEnergy')) 
			{
				if (isset($BatteryData['batteryAvailableEnergy']))
				{
					$this->SetValue("BatteryAvailableEnergy", $BatteryData['batteryAvailableEnergy']);
				}
				else
				{
					$this->LogMessage($this->Translate('update not possible, data not available'). " BatteryAvailableEnergy", KL_NOTIFY);
				}
			}
			if (@$this->GetIDForIdent('LastChangeBattery')) 
			{
				$this->SetValue("LastChangeBattery", (strtotime($BatteryData['timestamp'])));
			}
			if (@$this->GetIDForIdent('ActualKM')) 
			{
				$this->SetValue("ActualKM", $this->GetCockpitData());
			}
			if (@$this->GetIDForIdent('VIN')) 
			{
				$this->SetValue("VIN", $this->ReadAttributeString('VIN'));
			}
/*
			$this->RegisterPropertyBoolean('GPSLatitudeBool', false);
			$this->RegisterPropertyBoolean('GPSLongitudeBool', false);
			$this->RegisterPropertyBoolean('GPSUpdateBool', false);	
			$this->RegisterPropertyBoolean('GoogleMapsBool', false);
*/

			if ($this->ReadPropertyString('PhaseVersion') == "Phase_2")
			{
				if (($this->ReadPropertyBoolean('GPSLatitudeBool')) OR ($this->ReadPropertyBoolean('GPSLongitudeBool')) OR ($this->ReadPropertyBoolean('GPSUpdateBool')) OR ($this->ReadPropertyBoolean('GoogleMapsBool')))
				{
					
					$PosData = $this->GetPosition();
					if ($PosData)
					{
						if (@$this->GetIDForIdent('GPSLatitude')) 
						{
							$this->SetValue("GPSLatitude", $this->ReadAttributeFloat('GPSLatitude'));
						}
						if (@$this->GetIDForIdent('GPSLongitude')) 
						{
							$this->SetValue("GPSLongitude", $this->ReadAttributeFloat('GPSLongitude'));
						}
						if (@$this->GetIDForIdent('GPSUpdate')) 
						{
							@$this->SetValue("GPSUpdate", (strtotime($PosData['gpsUpdate'])));
						}
						if (@$this->GetIDForIdent('GoogleMapsHTML')) 
						{
							$this->SetPositionMaps();

						}
					}
				}	
			}
		}	
		
		public function HVAC()
		{
			return $this->startClimate();
		}

		public function RequestAction($Ident, $Value = NULL)
		{
			$this->$Ident($Value);
		/*	switch ($Ident) {
				case 'HVAC':
					$this->HVAC();
				break;
				case 'SetGigyaAPIID':
					$this->SetGigyaAPIID($Value);
				break;
				case 'GMAPS':
					$this->GMAPS($Value);
				break;
				}
				*/
		}

		private function GMAPS(bool $value)
		{
			$this->UpdateFormfield("GoogleAPIID","visible",$value);
		}

		/*
		public function GMAPSPhase2(string $value)
		{
			$FirstRunDone = $this->ReadAttributeBoolean('FirstRunDone')			;
			$Phase = false;
			if (($FirstRunDone == true) AND ($value == "Phase_2"))
			{
				$Phase = true;
			}
				$this->UpdateFormfield("GPSPanel","visible",$Phase);
		}
	*/
		public function SetFirstRunDoneManually()
		{
			$this->WriteAttributeBoolean('FirstRunDone', true);
		}
	}