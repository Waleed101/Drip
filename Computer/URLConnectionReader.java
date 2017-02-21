import java.net.*;
import java.io.*;
import java.util.Scanner;

public class URLConnectionReader {  
   static Scanner inData;
   static boolean reachedEnd = false;
   static int[] prevData = new int[4];
   static int[] data = new int[4];
   static String[] response = new String[255];
   static String[] writtenValues = new String[10000];
   static int curEntry = 0;
   static File nameFile = new File("sprinkler.txt");
   static PrintStream printToFile;
    public static void main(String[] args) throws Exception, InterruptedException{
      inData = new Scanner(new FileReader("12345.txt"));
      printToFile = new PrintStream(nameFile);
       while(true)
       {
        if(readValues())
           outputValues(0);
         
        outputValues(1);
        Thread.sleep(1000);
       }
    }
    
    static void outputValues(int type) throws Exception
    {   
      String url = "", response = "";
        switch (type)
        {
         case 0:
         url = "http://precisionfarming.tk/dashboard/employee/pages/uploaddata.php?esid=" + data[0] + "&data1=" + data[1] + "&data2=" + data[2] + "&data3=" + data[3];
         break;
         
         case 1:
         url = "http://precisionfarming.tk/dashboard/employee/pages/testcase.php?q=12345";
         break;
        }
        
        URL yahoo = new URL(url);
        URLConnection yc = yahoo.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                yc.getInputStream()));
        String inputLine;
        int currLine = 0;
        while ((inputLine = in.readLine()) != null) 
        {
            if(inputLine.substring(0,1).equals("O"))
               writeSprinkler(inputLine.substring(3, inputLine.length()-6));
        }    
        in.close();
    }
    
    static void writeSprinkler(String esid)
    {
      boolean printed = false;
      for(int i = 0; i < curEntry-1; i++)
         if(writtenValues[i].equals(esid))
            printed = true;
      
      if(!printed)
      {
         System.out.println(esid);
         printToFile.println(esid);
         writtenValues[curEntry] = esid;
         curEntry++;
      }
    }
    
    static boolean readValues() throws IOException
    {  
         String value = inData.nextLine();
         switch(value)
         {
            case "A":
            data[0] = inData.nextInt();
            break;
            
            case "B":
            data[1] = inData.nextInt();
            break;
            
            case "C":
            data[2] = inData.nextInt();
            break;
            
            case "D":
            data[3] = inData.nextInt();
            inData = new Scanner(new FileReader("12345.txt"));
            reachedEnd = true;
            break;
         }
         System.out.println("--------------------------------");
      System.out.println(data[0]);
      System.out.println(data[1]);
      System.out.println(data[2]);
      System.out.println(data[3]);
      if((data[0] != prevData[0] || data[1] != prevData[1] || data[2] != prevData[2] || data[3] != prevData[3]) && reachedEnd)
      {
         for(int i = 0; i < 4; i++)
            prevData[i] = data[i];
         reachedEnd = false;
         return true;
      }
      else
         return false;
      
    }
    
}