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

### 1. Funktionsumfang

* Auslesen von Batteriedaten (Akkustand, Restreichweite, Kapazität, usw)
* Auslesen der Fahrgestellnr und Gesamtkilometerstand

![grafik](https://user-images.githubusercontent.com/57233317/117500423-eb18ab00-af7c-11eb-850c-6cec8b82d0ba.png)

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.5

### 3. Software-Installation

* Über den Module Store das 'Renault Zoe'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'Renault Zoe'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name             | Beschreibung
---------------- | ----------------------------------------------------------------------------------------------------------
 Email Adresse   | Eingabe der Mailadresse mit der man in der MyRenault App angemeldet ist
 Passwort        | ratet mal ;)
 Modell          | Auswahl des Modells, Phase 1 Q90, R90, R110, Q210, R210, R240 oder Phase 2 R110 Z.E.40/50 und R135 Z.E.50
 Land		 | auswahl des Landes auf dass das Fahrzeug in der My Renaul App registriert ist.


### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name                              | Typ     | Beschreibung
--------------------------------- | ------- | ------------
Akkustand                         | integer | zeigt den Akkustand in % an
Batteriekapazität                 | integer | sollte die Batteriekapazität in kWh anzeigen,
Restreichweite in KM              | integer | theoretische Restreichweite laut Bordcomputer
Restkapazität in kWh              | integer | wieviel Rest ist im Akku in kWh
Ladekabelstatus                   | integer | steckt das Kabel im Fahrzeug (1) oder nicht (0)
Ladestatus                        | float   | 
Laderestzeit                      | integer | 
letzte Änderung Akkustand         | integer | letzte mitgeteilte Änderung des Akkustands
Gesamt-KM                         | integer | gesamt gefahrene KM
Fahrgestellnr.                    | string  | Fahrgestellnr. des Fahrzeugs


#### Profile

Name   | Typ
------ | -------
       |
       |

### 6. WebFront

derzeit gibt es nur Variablen zum anschauen.

### 7. PHP-Befehlsreferenz

kommen noch, hier gibt es zzt zuviele Änderungen.

`boolean ZOE_BeispielFunktion(integer $InstanzID);`
Erklärung der Funktion.

Beispiel:
`ZOE_BeispielFunktion(12345);`
