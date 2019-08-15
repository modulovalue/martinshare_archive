// i2c.c *****************************************************************************

#include <at89c5131.h>			// Registerdefinitionen													
//#include <intrins.h>				//	f�r Rotationsfunktion

//	!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!-!											
//****** verwendete I2C-Leitungen *********************************************************
#define SDA P4_1	// SDA-Leitung: 
#define SCL P4_0	// SCL-Leitung: 								
//****************************************************************************************


// *****************************************************************************************
// I2C- Routinen zur Ansteuerung eins I2C-Slaves
// I2C-Bus-Funktionen i2c_init, i2c_start, i2c_stop, i2c_schreiben, i2c_lesen auch f�r andere
// I2C-Bus-ICs verwendbar
// ******************************************************************************************/
sbit	acc7 = 0xE7;		//	f�r Aus- und Eingabe eines Bits des Akkus	
// **** Zeitverz�gerung zur Verlangsamung der Daten�bertragungsrate ***********************
// **** i=2 bis i=100 w�hlen je nach I2C-IC und Pull-Up-Widerstand
void i2c_zeit (void) {unsigned char i; for (i=5;i!=0;i--) ;}

// ****************************************************************************************
// ****** Initialiserung I2C-Bus **********************************************************
void i2c_init (void)
	{	
	SDA = 1;				//	Leitungen in den Grundzustand High										
	i2c_zeit ();	
	SCL	= 1;
	i2c_zeit ();
	}
	 
// ****** Startbedingung I2C-Bus **********************************************************
void i2c_start (void)
	{
	SDA = 1;			// Grundzustand
	SCL = 1;			// 
	i2c_zeit();
	SDA = 0;			// Wechsel SDA von 1 -> 0 w�hrend SCL = 1
	i2c_zeit ();
	SCL = 0;
	i2c_zeit ();
	}
	
//****** Stoppbedingung I2C-Bus ***********************************************************
void i2c_stop (void)
	{
	SDA = 0; // 
	SCL = 1;		
	i2c_zeit	();
	SDA = 1;	// Wechsel von SDA 0 -> 1 w�hrend SCL =1
	i2c_zeit ();
	}
	
//*****************************************************************************************
// * Byte ausgeben an I2C-Bus , R�ckgabewert = Acknoledgebit = 0 wenn erfolgreich 	
// ****************************************************************************************
bit i2c_schreiben (unsigned char byte)
	{
	unsigned char z;				// Z�hler 																	
	bit ack;							// Acknoledge-Bit															
	ACC = byte;						//	Rotationsregister Akku												
	for (z = 8; z != 0; z --)	//	Z�hler: serielle Ausgabe von 8 Bit								
		{	
		SDA = acc7;				//	Bit7 des Akku ausgeben												
		i2c_zeit	();										
		SCL = 1;					//	Daten sind g�ltig													
		asm {0x23};					//	Akku links schieben
		//ACC = _crol_ (ACC,1);	// links rotieren, Funktion von intrins.h							 
		i2c_zeit	();
		SCL = 0;					//	Datenausabe beendet													
		i2c_zeit	();
		}
	SDA = 1;					//	Leitung freigeben f�r Acknoledge-Bit								
	i2c_zeit	();
	SCL = 1;					//	Slave kann best�tigen													
	i2c_zeit ();						// warten																		
	ack = SDA;				// Acknoledge-Bit lesen														
	i2c_zeit	();
	SCL = 0;					// Slave soll Ackn-Ausgabe beenden	
	i2c_zeit();									
	return (ack);				// Acknoledge-Bit ist R�ckgabewert der Funktion	
									//	ack = 0 bedeutet "verstanden" !!!!!!						
	}


//*****************************************************************************************
// * Byte einlesen vom I2C-Bus. Bei Ack=1 wird Acknoledgebit gesendet, sonst nicht
// *****************************************************************************************
unsigned char i2c_lesen (bit ack)
	{
	unsigned char z,byte;
	SDA = 1;								//	Leitung freigeben (high) f�r Daten						
	for (z = 8; z != 0; z --)			//	Z�hler: serielles Einlesen von 8 Bit					
		{
		SCL = 1;							//	Daten sind g�ltig												
		i2c_zeit	();
		acc7 = SDA;						//	Datenbit in den Akku													
		asm {0x23};							//	Akku links schieben 
		// ACC = _crol_ (ACC,1);			// links rotieren, Funktion von intrins.h		
		//P2_7 = SDA;
		i2c_zeit(); 	
		SCL = 0;							//	Daten lesen beendet	
		i2c_zeit();										
		}
	byte = ACC;								// Eingelesenes Byte 											
	if (ack == 1)		{SDA = 0;}	// Acknowledge-Bit senden										
					else	{SDA = 1;}	//	kein Acknowledge-Bit													
	i2c_zeit	();		
	SCL = 1;								//	Ackn. g�ltig													
	i2c_zeit	();

	SCL = 0;								// Ackn. beendet													
	i2c_zeit	();
	SDA = 0;								// Leitung SDA vorbereiten f�r Stoppbed.				
	i2c_zeit ();
	return (byte);							//	eingelesenes Byte = R�ckgabewert													
   }
   















