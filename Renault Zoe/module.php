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
			
			$this->RegisterPropertyString("TokenID", '');
			$this->RegisterPropertyString('PersonID', '');
			$this->RegisterPropertyString("AccountID", '');

			$this->RegisterPropertyString('CarPicture', '');
			$this->RegisterPropertyBoolean('CarPicturebool', false);

			$this->RegisterPropertyString('VIN', '');
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

		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
		   //we will wait until the kernel is ready
			$this->RegisterMessage(0, IPS_KERNELMESSAGE);

			//Never delete this line!
			parent::ApplyChanges();
			
			if (IPS_GetKernelRunlevel() !== KR_READY) {
				return;
			}
			
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
					$this->RegisterVariableInteger('PlugStatus', $this->Translate('Plug Status'));
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
					$this->RegisterVariableInteger('ActualKM', $this->Translate('Actual KM'));
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
					$this->SetValue("VIN", $this->ReadPropertyString('VIN'));
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
	
		}

		public function GetConfigurationForm()
			{
				$jsonForm = json_decode(file_get_contents(__DIR__ . "/form.json"), true);

				if ($this->ReadPropertyString('PhaseVersion') == "Phase_2")
				{
					$jsonForm["elements"][5]["items"][1]["visible"] = false;
					$jsonForm["elements"][5]["items"][2]["visible"] = true;
				}
				if ($this->ReadPropertyString('PhaseVersion') == "Phase_1")
				{
					$jsonForm["elements"][5]["items"][1]["visible"] = true;
					$jsonForm["elements"][5]["items"][2]["visible"] = false;
				}

				$jsonForm["elements"][4]["value"] = $this->ReadAttributeString('Country');
				$jsonForm["elements"][6]["items"][1]["value"] = $this->ReadAttributeString('GigyaAPIID');
				return json_encode($jsonForm);
			}

		public function FirstRun(){
			// hier tokenscript einfügen
			// token muss regelmäßig. ca alle 24 Stunden geholt werden
			/* ZOE_GetAccountID($this->InstanceID); 
			ZOE_SetGigyaAPIID($this->InstanceID);
			ZOE_GetToken($this->InstanceID);
			*/
			}
	
		public function UpdateToken(){
			// hier tokenscript einfügen
			// token muss regelmäßig. ca alle 24 Stunden geholt werden
			ZOE_GetToken($this->InstanceID);
		}

		public function UpdateData(){
			$BatteryData = ZOE_GetBatteryData($this->InstanceID);
			if ( $BatteryData['ERRORKAMERON'] == true) {
				$this->LogMessage("can not update Data, please check Kameron API ID", KL_ERROR);
				exit;	
			 };
			if ( $BatteryData['ERROR'] == true) {
				$this->LogMessage("i have a problem to update data, renew token.", KL_WARNING);
				ZOE_GetToken($this->InstanceID);
				$BatteryData = ZOE_GetBatteryData($this->InstanceID);
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

		}
	}