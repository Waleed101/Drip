//Include all libraries
#include "RF24.h"
#include "RF24Network.h"
#include "RF24Mesh.h"
#include <SPI.h>

//Define NRF24L01's CE and CSN pins
RF24 radio(7, 8);
RF24Network network(radio);
RF24Mesh mesh(radio, network);

/*
  Define the specific node id, which can be an ID between the range of the 1 - 255, where 
  0 is reserved for the master node.
*/
#define nodeID 2

// Display timer is used to make sure that the node only transmits every second
uint32_t displayTimer = 0;

struct payload_t { // Struct payload holding the to be transmitted values
  int temp;
  int mstrval;
};
typedef struct payload_t Package;
Package data;

void setup() {
  //Intilize the Serial and Mesh libraries
  Serial.begin(115200);
  mesh.setNodeID(nodeID);
  Serial.println(F("Connecting to the mesh..."));
  mesh.begin();
}

void loop() {
  //Reupdate Mesh connection
  mesh.update();
  
  readData(); //Run Read Data function
  if (millis() - displayTimer >= 1000) { // Check if it has been a second
    displayTimer = millis();

    if (!mesh.write(&data, 'M', sizeof(data))) { //Write the data with header 'M'

      if ( ! mesh.checkConnection() ) { //Check if connection to mesh is still true
        Serial.println("Renewing Address");
        mesh.renewAddress();
      } else {
        Serial.println("Send fail, Test OK");
      }
    } else { //Print sent data to prompt
      Serial.print("Send OK: "); Serial.println(data.temp);Serial.println(data.mstrval);
    }
  }

  while (network.available()) { // Check to see if other nodes are sending data
    RF24NetworkHeader header;
    network.read(header, &data, sizeof(data));
    Serial.print("Received Temperature");
    Serial.print(data.temp);
    Serial.print(" with Moisture Value ");
    Serial.println(data.mstrval);
  }
}


void readData() // Read moisture and temperature values
{
  data.mstrval = analogRead(A1);
  Serial.print("Moisture Value: ");
  Serial.println(data.mstrval);
  data.temp = analogRead(A0);
  Serial.print("Temperature Value: ");
  Serial.println(data.temp);
}




