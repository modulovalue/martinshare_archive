#include "at89c5131.h"				// fuer Atmel AT89C5131
#include "i2c.c"					// I2C-Routinen
#include "lcd.c"					// LCD-Anzeige
#include "stdio.h"				    // sprintf...

//Konstanten- Variablendeclaration	
char ADC, S2_ctr;             
unsigned char buf [16];				// Text-Buffer für LCD
unsigned int i2c_Port,j,z,t;
                     
// ---------- Funktionen ----------------------------------------------------------------
void zeit (unsigned int sec)								
	{												
     for (z= sec; z != 0; z--);        
   }    

void ADU (void)
	{
        P2 = 0x11;
        i2c_init ();					//	I2C-Bus initialisieren	(Grundzustand)						
        i2c_start ();					//	Startbedingung I2C-Bus ausgeben							
        i2c_schreiben (0x90);		    //	Adresse des IC und Schreibwunsch (zur Kanalwahl)		
        i2c_schreiben (0x00);	        //	Controll-Byte mit Kanalnummer					
        i2c_stop();
        i2c_start ();					//	Startbedingung I2C-Bus ausgeben								
        i2c_schreiben (0x91);			//	Adresse des IC und Lesewunsch									
        ADC = i2c_lesen (1);					//	neue AD-Wandlung	Acknowledge = 1								
        ADC = i2c_lesen (0);			//  Wert abholen		Acknowledge	=0									
        i2c_stop ();					//	Stoppbedingung I2C-Bus ausgeben			
   
        loeschenlcd();                  
        sprintf (buf,"I2C-AD-Wandler"); //  String in buffer
        textlcd (buf,1);                //  Ausgabe buffer an LCD, Zeile 1
        sprintf (buf,"Wert:%3d",ADC);    //  String und dezimalzahl in buffer
        textlcd (buf,2);                //  Ausgabe buffer in Zeile 2
   }

 //----------- Hauptprogramm             
void main (void)
{
	initlcd();						// LCD initialisieren
	loeschenlcd();
	
	sprintf (buf,"JDS-Rastatt");
    textlcd (buf,1); 
 	sprintf (buf,"Weiter mit S2.");
    textlcd (buf,2);
		
	while (1) // Endlosschleife Hauptprogramm	------------------------------------------------												
		{		      
		     ADU();			
             zeit(20000);    
		} // von while
}// von main