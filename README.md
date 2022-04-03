# Renault Zoe
Modul für Symcon ab 5.5
Erlaubt es eine Renault Zoe Phase 1/2 an Symcon anzubinden und Fahrzeug- sowie Batteriedaten auszulesen und wahlweise als Variable zu speichern.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
8. [Bekannte Bugs](#8-bekannte-bugs)

### 1. Funktionsumfang

* Auslesen von Batteriedaten (Akkustand, Restreichweite, Kapazität, usw)
* Auslesen der Fahrgestellnr und Gesamtkilometerstand
* starten der Klimatisierung

![grafik](https://user-images.githubusercontent.com/57233317/117500423-eb18ab00-af7c-11eb-850c-6cec8b82d0ba.png)

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.5
- Einen Renault Zoe Phase 1/2, Renault Twingo oder einen Dacia Spring

### 3. Software-Installation

* Über den Module Store das 'Renault ZE'-Modul installieren.

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'Renault ZE'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name             | Beschreibung
---------------- | ----------------------------------------------------------------------------------------------------------
 Email Adresse   | Eingabe der Mailadresse mit der man in der MyRenault App angemeldet ist
 Passwort        | ratet mal ;)
 Modell          | Auswahl des Modells, 
                   Phase 1 Q90, R90, R110, Q210, R210, R240
                   Phase 2 R110 Z.E.40/50 und R135 Z.E.50
                   Renault Twingo
                   Dacia Spring
 Land		 | auswahl des Landes auf dass das Fahrzeug in der My Renaul App registriert ist.


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name                                  | Typ     | Beschreibung
------------------------------------- | ------- | ------------
Akkustand                             | integer | zeigt den Akkustand in % an
Batteriekapazität                     | integer | zeigt die Batteriekapazität in kWh an,
Fahrgestellnr.                        | string  | Fahrgestellnr. des Fahrzeugs
Gesamt-KM                             | integer | gesamt gefahrene KM
Google Maps                           | string  | Google maps Karte
GPS Latitude                          | float   | GPS Latitude
GPS Longitude                         | float   | GPS Longitude
Klimatisierung                        | boolean | Script zum starten der Klimatisierung
Ladekabelstatus                       | integer | gibt den Zustand des Ladeports an. Steckt das Kabel im Fahrzeug (1) oder nicht (0)
Laderestzeit                          | integer | Restladedauer (unplausible Werte)
Ladestatus                            | float   | 
letzte Änderung Akkustand             | integer | letzte mitgeteilte Änderung des Akkustands
letzte Änderung der GPS Koordination  | integer | letzte mitgeteilte Änderung der GPS Koordination
Restkapazität in kWh                  | integer | wieviel Rest ist im Akku in kWh
Restreichweite in KM                  | integer | theoretische Restreichweite laut Bordcomputer
Fahrzeugbild                          | media   | das Fahrzeugbild


#### Profile

Name                   | Typ     | Beschreibung
---------------------- | ------- | ------------
RZE_ChargingStatus     | float   | div Information zum Ladestatus

### 6. WebFront

neben auswählbaren Fahrzeugbezogenen Variablen, lässt sich, je nach Modell und aktivierten und vorhandener Googlemaps-API Key der Standort vom Fahrzeug anzeigen und auf einer HTML-Map anzeigen. Zudem kann man, sofern aktiviert, die Vorklimatisierung starten.

### 7. PHP-Befehlsreferenz

`RZE_FirstRun(int $InstanceID);`

INTERNE FUNKTION: ist eine interne Funktion und ruft nacheinander die API ID ab und aktualisert einmalig alle Daten.

`RZE_GMAPS(int $InstanceID, bool GoogleMapsBool);`

INTERNE FUNKTION: eine interne Funktion die das Konfigurationsformular bereitstellt wenn man Google-Maps aktivieren möchte.

`RZE_GMAPSPhase2(int $InstanceID, string $PhaseVersion);`

INTERNE FUNKTION: eine interne Funktion die im Konfigurationsformular GPS variablen erstellt wenn man Google-Maps aktivieren möchte und eine Zoe Phase 2 besitzt.

`RZE_GetAccountID(int $InstanceID);`

INTERNE FUNKTION: wird von der Funktion RZE_FirstRun() aufgerufen und holt die AccountID ab.

`RZE_GetBatteryData(int $InstanceID);`

INTERNE FUNKTION: fragt die Batteriedaten ab und stellt diese als Array bereit.

`RZE_GetCarInfos(int $InstanceID);`

INTERNE FUNKTION: holt diverse Fahrzeuginformation ab und stellt diese als Array bereit.

`RZE_GetCockpitData(int $InstanceID);`

INTERNE FUNKTION: holt sich den Gesamt-KM Stand ab.

`RZE_GetPosition(int $InstanceID);`

INTERNE FUNKTION: holt sich die GPS Daten (langtitude und longitude) ab.

`RZE_GetToken(int $InstanceID);`

INTERNE FUNKTION: holt sich die AccountID und PersonID ab

`RZE_HVAC(int $InstanceID);`

INTERNE FUNKTION: ruft die Funktion RZE_startClimate() auf

`RZE_SetGigyaAPIID(int $InstanceID);`

INTERNE FUNKTION: setzt die Gigya-API-ID abhängig vom Land

`RZE_UpdateData(int $InstanceID);`

INTERNE FUNKTION: aktualsiert alle Daten und setzt die Variablen

`RZE_UpdateToken(int $InstanceID);`

INTERNE FUNKTION: ruft intervallmäßig die Funktion RZE_GetToken() auf.

`RZE_setCarMedia(int $InstanceID);`

INTERNE FUNKTION: holt sich das Fahrzeugbild ab und speichert es als Medienobjekt

`RZE_startClimate(int $InstanceID);`

INTERNE FUNKTION: Funktion zum starten der Vorklimatisierung

`RZE_SetFirstRunDoneManually(int $InstanceID);`

Betatester die eine Instanz vor Version 1.2 installiert haben, müssen diese Funktion einmalig ausführen. Am einfachsten führt Ihr ein Rechtsklick auf eure Instanz auf, wählt "Befehle testen" und sucht nach obiger Funktion und klickt auf "ausführen". Nun könnt Ihr in der Instanz wieder alle Optionspanele sehen.

### 8. Bekannte Bugs

* Optionspanele einer vorhanden Instanz sind verschwunden

Wer eine Beta vor Version 1.1 installiert hat, wird beim öffnen der Instanz feststellen, das die Panele für die Variablenauswahl, erweitere Optionen und ggf auch das Panel für die GPS Daten nicht mehr vorhanden sind. Grund ist, das diese erst nach dem durchlaufen des "First Run" sichtbar gemacht werden. Diese Funktion ist aber nach dem ausführen nicht mehr auswählbar. 

Am einfachsten führt Ihr ein Rechtsklick auf eure Instanz auf, wählt "Befehle testen" und sucht nach der Funktion "RZE_SetFirstRunDoneManually" und klickt auf "ausführen". Nun könnt Ihr in der Instanz wieder alle Optionspanele sehen.