#include <SPI.h>
#include <MFRC522.h>

#define RST_PIN   9     // Configurable, see typical pin layout above
#define SS_PIN    10    // Configurable, see typical pin layout above

MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance

MFRC522::MIFARE_Key key;

byte joserraUID[4];
byte guest[4];

int pirPin = 7; // Input for HC-S501

int buzzer = 8; //the pin of the active buzzer

int pirValue;   // Place to store read PIR Value

int lGreen = 2; // Alarm ON
int lRed = 3;   // Alarm OFF
int lBlue = 4;  // Outside light

bool stateAlarm = false;
int ndetections = 0;
int oneTime = 0;

void setup()
{
  pinMode(pirPin, INPUT); //Movement sensor INPUT
  
  pinMode(buzzer, OUTPUT);//initialize the buzzer pin as an output
  
  pinMode(lGreen, OUTPUT);
  pinMode(lRed, OUTPUT);
  digitalWrite(lRed, HIGH);
  pinMode(lBlue, OUTPUT);

  //Initialize the serial port
  Serial.begin(9600); //Initialise the communication with the Rasp
  while (!Serial);    // Wait untilSerial is ready - Leonardo

  //Initialize the RFC reader
  SPI.begin();         // Init SPI bus
  mfrc522.PCD_Init();  // Init MFRC522 card
  
  // Prepare key - all keys are set to FFFFFFFFFFFFh at chip delivery from the factory.
  for (byte i = 0; i < 6; i++)
  {
    key.keyByte[i] = 0xFF;
  }

  //Key Chain -> Joserra
  joserraUID[0] = 0x19;
  joserraUID[1] = 0xEF;
  joserraUID[2] = 0x2A;
  joserraUID[3] = 0xB9;

  //RFC card -> Guests
  guest[0] = 0x69;
  guest[1] = 0xD1;
  guest[2] = 0xF6;
  guest[3] = 0x97;
}

void rfidRead()
{
  int matches = 0;
  
  // Look for new cards, and select one if present
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial())
  {
    delay(50);
    return;
  }
  
  // Now a card is selected. The UID and SAK is in mfrc522.uid.
  
  // Now, print the UID of the Card
  //Serial.print(F("Card UID:"));
  for (byte i = 0; i < mfrc522.uid.size; i++)
  {
    //Serial.print(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " ");
    //Serial.print(mfrc522.uid.uidByte[i], HEX);

    if(mfrc522.uid.uidByte[i] == joserraUID[i] || mfrc522.uid.uidByte[i] == guest[i])
    {
      matches++;
    }
  } 
  //Serial.println();

  if(matches == 4)
  {
    //Serial.println("Bienvenido");
    //Serial.println();

    stateAlarm = !stateAlarm;
    ndetections = 0;
    //Serial.println(stateAlarm);
  }
  
  delay(1500);
}

void raspCommands()
{
  if (Serial.available())
  {
    char c = Serial.read(); //Store the command in a variable
    if (c == 'H')
    { 
      //Turn ON the alarm
      stateAlarm = true;
      ndetections = 0;
    } else if (c == 'L')
    { 
      //Turn OFF the alarm
      stateAlarm = false;
      ndetections = 0;
    }
  }
}

void loop()
{
  rfidRead();
  raspCommands();

  Serial.println(stateAlarm);
  Serial.println(ndetections);

  //Checks if the alarm is on or off
  if(stateAlarm)
  {    
    digitalWrite(lGreen, HIGH);
    digitalWrite(lRed, LOW);

    pirValue = digitalRead(pirPin);
//    Serial.println("pirValue: ");
//    Serial.print(pirValue);
//    Serial.println();
  
    //if pirValue = 1 --> Movement
    //if pirValue = 0 --> No movement
  
    while(pirValue == 1)
    {
      if(oneTime == 0)
      {
        ndetections++;
        oneTime++;
      }
        digitalWrite(lBlue, HIGH);
        digitalWrite(buzzer, HIGH);
        delay(2);//wait for 2ms
        digitalWrite(buzzer, LOW);
        delay(2);//wait for 2ms
        pirValue = digitalRead(pirPin);
    }
    oneTime = 0;
    digitalWrite(lBlue, LOW);
  }
  else
  {
    digitalWrite(lGreen, LOW);
    digitalWrite(lRed, HIGH);

    pirValue = digitalRead(pirPin);
//    Serial.println("pirValue: ");
//    Serial.print(pirValue);
//    Serial.println();
  
    //if pirValue = 1 --> Movement
    //if pirValue = 0 --> No movement
  
    while(pirValue == 1)
    {
      digitalWrite(lBlue, HIGH);
      pirValue = digitalRead(pirPin);
//      Serial.println("pirValue: ");
//      Serial.print(pirValue);
//      Serial.println();
    }
    digitalWrite(lBlue, LOW);
  }
}
