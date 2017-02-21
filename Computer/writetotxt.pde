import processing.serial.*;
Serial mySerial;
PrintWriter output;
boolean reset = false;
void setup() {
   mySerial = new Serial( this, Serial.list()[1], 9600 );
   output = createWriter( "12345.txt" );
}
void draw() {
    if (mySerial.available() > 0 ) {
         String value = mySerial.readString();
         if ( value != null ) {
              if(reset)
              {
                println("Reseting Values!");
                reset = false;
              }
              println(value.length);
              if(value.substring(0,1).equals('E'))
              {
                println("RESETING");
                reset = true;
              }
              output.println( value );
              println( value );
              output.flush();
              
         }
    }
}

void keyPressed() {
    output.flush();  // Writes the remaining data to the file
    output.close();  // Finishes the file
    exit();  // Stops the program
}