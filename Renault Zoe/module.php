<?php

declare(strict_types=1);
require_once __DIR__ . '/../libs/functions.php';

	class RenaultZoe extends IPSModule
	{
		use Helper;
		public function Create()
		{
			//Never delete this line!
			parent::Create();
		
			$this->RegisterPropertyString('LoginMailAddress', '');
			$this->RegisterPropertyString('Password', '');

			$this->RegisterPropertyString('PhaseVersion', 'Phase_2');
			$this->RegisterAttributeString('Country', 'DE');
			
			$this->RegisterAttributeString('GigyaAPIID', '');

			$this->RegisterPropertyString('KameronAPIID', 'Ae9FDWugRxZQAGm3Sxgk7uJn6Q4CGEA2');
			
			$this->RegisterAttributeString("TokenID", '');
			$this->RegisterAttributeString('PersonID', '');
			$this->RegisterAttributeString("AccountID", '');

			$this->RegisterPropertyString('CarPicture', '');
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

			//$this->RegisterTimer('ZOE_UpdateData', 0, 'ZOE_UpdateData('.$this->InstanceID.');');
			
			$this->RegisterTimer('ZOE_UpdateData', 0, 'ZOE_UpdateData($_IPS[\'TARGET\']);');
			$this->RegisterPropertyInteger('UpdateDataInterval', 10);

		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
			$this->UnregisterTimer('ZOE_UpdateData');

		}

		public function ApplyChanges()
		{
	
			//Never delete this line!
			parent::ApplyChanges();
					
			if ($this->ReadPropertyBoolean('BatteryLevelbool')) 
			{
				if (!@$this->GetIDForIdent('BatteryLevel')) 
				{
					$this->RegisterVariableInteger('BatteryLevel', $this->Translate('Battery Level'));
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
					$this->RegisterVariableInteger('PlugStatus', $this->Translate('Plug-Status'));
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
					$this->RegisterVariableFloat('ChargingStatus', $this->Translate('Charging Status'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("ChargingStatus");
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
					$this->RegisterVariableInteger('HVAC', $this->Translate('Climate Control'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("HVAC");
				}

			if ($this->ReadPropertyBoolean('CarPicturebool')) 
			{
				if (!@$this->GetIDForIdent('CarPicture')) 
				{
					$this->RegisterVariableString('CarPicture', $this->Translate('CarPicture'), "~HTMLBox");
					$this->SetValue("CarPicture", $this->ReadPropertyString('CarPicture'));
				}
			} 
			else 
				{
					$this->UnregisterVariable("CarPicture");
				}	

				$this->SetGigyaAPIID($this->ReadAttributeString('Country'));
				if ($this->ReadAttributeString('PersonID')){
					$this->UpdateData();
				}
				$this->SetTimerInterval('ZOE_UpdateData', $this->ReadPropertyInteger('UpdateDataInterval') * 60 * 1000);

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

			if ($this->ReadAttributeString('PersonID')){
				$jsonForm["actions"][0]["visible"] = false;
				$jsonForm["actions"][1]["visible"] = false;
				$jsonForm["actions"][3]["visible"] = true;
				$jsonForm["actions"][4]["visible"] = true;
				$jsonForm["actions"][5]["visible"] = true;
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
			$this->ReloadForm();
		}
	
		public function UpdateToken(){
			$this->GetToken();
		}

		public function UpdateData()
		{
			$BatteryData = $this->GetBatteryData();
			if ( $BatteryData['ERRORKAMERON'] == true) {
				$this->LogMessage("can not update Data, please check Kameron API ID", KL_ERROR);
				exit;	
			 };
			if ( $BatteryData['ERROR'] == true) {
				$this->LogMessage("i have a problem to update data, renew token.", KL_WARNING);
				$this->GetToken();
				$BatteryData = $this->GetBatteryData();
			 };
			 if ( $BatteryData['ERROR'] == true) {
				$this->LogMessage("update not possible, renew token failed.", KL_WARNING);
				exit;
			 };
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
            */			//print_r($BatteryData);
			
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
				$this->SetValue("BatteryCapacity", $BatteryData['batteryCapacity']);
			}
			if (@$this->GetIDForIdent('PlugStatus')) 
			{
				$this->SetValue("PlugStatus", $BatteryData['plugStatus']);
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
				$this->SetValue("BatteryAvailableEnergy", $BatteryData['batteryAvailableEnergy']);
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
		}	
		public function SetUpdateIntervall(int $Minutes = null){
			if (!($Minutes > 0)) {
				$Minutes = $this->ReadPropertyInteger('UpdateDataInterval');
			}
			$interval = $Minutes * 60 * 1000;
			$this->SendDebug(__FUNCTION__, 'minutes=' . $Minutes, 0);
			$this->SetTimerInterval('ZOE_UpdateData', $interval);
			
		}
	}