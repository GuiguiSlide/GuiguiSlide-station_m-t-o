#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>
#include <LiquidCrystal.h>
#include <NTPClient.h>
#include <WiFiUdp.h>


// Paramètres du réseau Wi-Fi
const char* ssid = "iPhone 14 PRO de Téo";
const char* password = "DexlaZ29";
const int rs = 16, en = 17, d4 = 5, d5 = 18, d6 = 19, d7 = 21;
int temp;
int hum; 
float tempe;
float humi;

LiquidCrystal lcd(rs, en, d4, d5, d6, d7);

// Initialisation du capteur DHT22
#define DHTPIN 4
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP);
String formattedDate;
String dayStamp;
String timeStamp;

const char* serverName = "http://172.20.10.3/relevee.php"; 

// Fonction pour envoyer les données au serveur
void sendDataToServer(float temp, float hum) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(serverName);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        
        String postData = "temperature=" + String(temp) + "&humidite=" + String(hum);

        
        Serial.println("---- Envoi des données ----");
        Serial.print("Température : ");
        Serial.print(temp);
        Serial.println(" °C");
        tempe=temp;

        Serial.print("Humidité : ");
        Serial.print(hum);
        Serial.println(" %");
        humi=hum;

        
        int httpResponseCode = http.POST(postData);

        
        Serial.print("Code HTTP : ");
        Serial.println(httpResponseCode);

        
        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.println("Réponse du serveur : " + response);
        } else {
            Serial.println("Erreur lors de l'envoi des données.");
        }

        http.end();
    } else {
        Serial.println("Pas de connexion WiFi !");
    }
}


void setup() {

     lcd.begin(16, 2);
    lcd.print("  172.20.10.3");

    Serial.begin(115200);
    dht.begin();
    delay(2000);

   
    Serial.print("Connexion à ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);

    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < 20) {
        delay(1000);
        Serial.print(".");
        attempts++;
    }

    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\nWiFi connecté !");
        Serial.print("Adresse IP : ");
        Serial.println(WiFi.localIP());
    } else {
        Serial.println("\nÉchec de connexion WiFi !");
    }
    timeClient.begin();
  // Set offset time in seconds to adjust for your timezone, for example:
  // GMT +1 = 3600
  // GMT +8 = 28800
  // GMT -1 = -3600
  // GMT 0 = 0
  timeClient.setTimeOffset(3600);
}


void loop() {

    lcd.setCursor(0, 1);
    lcd.print(tempe);
    lcd.print("C ");
    lcd.print(humi);
    lcd.print("H% ");
    
    float temperature = dht.readTemperature();
    float humidite = dht.readHumidity();

    if (!isnan(temperature) && !isnan(humidite)) {
        
        sendDataToServer(temperature, humidite);
    } else {
        Serial.println("Erreur de lecture du capteur !");
    }
    struct tm timeinfo;
    if (!getLocalTime(&timeinfo)) {
        Serial.println("Failed to obtain time");
    }
    while(!timeClient.update()) {
    timeClient.forceUpdate();
    }
    formattedDate = timeClient.getFormattedTime();
    int splitT = formattedDate.indexOf("T");
    timeStamp = formattedDate.substring(splitT+1, formattedDate.length()-1);
    lcd.print(timeStamp);
    lcd.setCursor(14, 1);
    lcd.print("  ");
    delay(10000); 
}