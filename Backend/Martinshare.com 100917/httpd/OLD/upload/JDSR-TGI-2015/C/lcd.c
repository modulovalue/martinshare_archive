/* lcd.c **********************************************************************************
 *        Funktionen zur Ansteuerung des LCD-Displays
 ******************************************************************************************/
 
									
#include <intrins.h>									// für _crol_ -Funktion									
#include <stdio.h>								//	für sprintf													

//unsigned char LCD_PORT;	
#define LCD_PORT P0 								//	LCD-Display an P0 im Port-4-Bit-Modus		

void initlcd (void);													// Initialisierung
void textlcd (unsigned char *text, unsigned char zeile);	// Textausgabe in Zeile 1 bis 4
void definierelcdsymbol (unsigned char pixelprozeile [8],unsigned char adr);
														// Definition von max 7 eigenen Zeichen Adr 1 bis 7
void LCDbefehl (unsigned char befehl);		// Ausgabe von Befehlen laut Datenblatt
void charlcd (unsigned char zeichen);		// Ausgabe eines Zeichens an die aktuelle Cursorposition
void cursorpos (unsigned char position);	// Setzen der Cursorposition

/******************************************************************************************
 *  Zeitverzögerung:  wartet ca.  anzahl mal 100µs                                                     
 ******************************************************************************************/
void warte100u (unsigned char anzahl)	
	{
	unsigned char z1,z2;
	for (z2 = anzahl;	z2 != 0;	--z2)
		{
		for (z1 = 255; 	z1!= 0; 	--z1);
		};
	}

/*****************************************************************************************
 * 	Gibt das Byte  befehl  als Befehl an LCD-Display im 4Bit-Modus                              
 *      Entweder Busy-Flag des LCD abfragen oder Zeitverzögerung verwenden        
 *****************************************************************************************/
void LCDbefehl (unsigned char befehl)		
	{	
	unsigned char a1;
	a1 = befehl;
	a1 = _crol_ (a1,4); 			// in intrins.h, char um 4 Bits rolieren -> High und Lownibbel vertauschen 													
	a1 = (a1 & 0x0F) | 0x10;	//	4 Bits maskieren, Übergabetakt = 1, High-Nibbel senden	
	LCD_PORT = a1;
	LCD_PORT = a1 & 0x00;		// Takt = 0 																
	a1 = befehl;					// Low-Nibbel																
	a1 = (a1 & 0x0F) | 0x10;	//	4 Bits maskieren, Übergabetakt = 1								
	LCD_PORT = a1;
	LCD_PORT = a1 & 0x00;		// Takt = 0 																
	
/***** Busy-Abfrage ***********************************************************************	
	do {
		LCD_PORT = 0x5F;			//  Busy lesen, Takt=1, RS = 0										
		a1 = LCD_PORT	;			//	und holen																
		LCD_PORT = 0x4F;			//	Takt=0																	
		a = a;
		LCD_PORT = 0x5F;			//	Low-Byte holen (ohne Bedeutung)									
		LCD_PORT = 0x4F;		
		}
	while ( (a1 & 0x08) != 0 ); //	warten solange Busy high										
 ***** Ende Busy-Abfrage ******************************************************************/
	
/*	wenn Busy nicht geht, Zeitverzögerung 1ms verwenden 	**********************************/
	warte100u (10);
	}

/***********************************************************************************
 * Pixelweise Definition eigener LCD-Zeichen, Ablegen von max 7 Zeichen im CG-RAM des LCD
 ***********************************************************************************/
void definierelcdsymbol (unsigned char pixelprozeile [8],unsigned char adr)
	{unsigned char z;
	if ((adr>0) & (adr<8)) 									// Adr0 geht nicht !????, max Adr 7
	LCDbefehl (((adr*8) & 0x7F) | 0x40);	
	// Adresse der ersten Pixelzeile = 8 x Adresse des fertigen Zeichens im DD Ram
	// Zugriff auf Adresse im CG-RAM (Character Generator) mit  Bit7 = 0, Bit6 = 1
	for (z=0;z<8;z++)	charlcd (pixelprozeile[z]);	//	8 Pixelzeilen ins CR-Ram
	cursorpos (0);												// wieder auf DD-Ram-Zugriff umschalten		
	}
	
/******************************************************************************************
 * Initialisierung des LCD- Displays für den 4-Bit-Modus                                               
 ******************************************************************************************/
void initlcd (void)				
	{ 
	LCD_PORT = 0x13;			//	aufwecken! 	Takt=1	00010011											
	LCD_PORT = 0x03;			//					Takt=0	00000011											
	warte100u (50);			//	5ms warten		       													
	LCD_PORT = 0x13;			//	aufwecken! 	Takt=1														
	LCD_PORT = 0x03;			//					Takt=0														
	warte100u (1);				//	100us warten		      												
	LCD_PORT = 0x13;			//	aufwecken! 	Takt=1														
	LCD_PORT = 0x03;			//				Takt=0															
	warte100u (1);				//	100us warten		       												
	LCD_PORT = 0x12;			//	8->4  BITS UMSCHALTEN,	Takt=1										
	LCD_PORT = 0x02;   		//						Takt=0													
	warte100u (1);				//	100us warten		       												
	LCDbefehl (0x28);			//	Function set 4 bits  													
	LCDbefehl (0x0C);			//	Display AN, Cursor AUS													
	LCDbefehl (0x06);			//	Not Shifted Display, Increment				
	}

/******************************************************************************************
 * Display löschen	                                                                                                      
 ******************************************************************************************/
void loeschenlcd (void)		
	{	LCDbefehl (0x01);	}

/*****************************************************************************************
 * 	Ausgabe eines Zeichens an das LCD-Display                                                          
 *      Entweder Busy-Flag des LCD abfragen oder Zeitverzögerung verwenden         
 ******************************************************************************************/
void charlcd (unsigned char zeichen)	
	{
	unsigned char a1;
	a1 = zeichen ;	
//	al >> 4;
	a1 = _crol_ (a1,4); 	// in intrins.h, char um 4 Bits rolieren -> High und Lownibbel 
								//	vertauschen im Debugger getestet, geht 																			
	a1 = (a1 & 0x0F) | 0x90;	//	4 Bits maskieren, Übergabetakt = 1, High-Nibbel senden	
	LCD_PORT = a1;
	LCD_PORT = a1 & 0x80;		// Takt = 0 																
	a1 = zeichen;					// Low-Nibbel																
	a1 = (a1 & 0x0F) | 0x90;	//	4 Bits maskieren, Übergabetakt = 1								
	LCD_PORT = a1;
	LCD_PORT = a1 & 0x80;		// Takt = 0 																
	
/**** Busy-Abfrage ***********************************************************************	
	do {
		LCD_PORT = 0x5F;			//  Busy lesen, Takt=1, RS = 0										
		a1 = LCD_PORT	;			//	und holen																
		LCD_PORT = 0x4F;			//	Takt=0																	
		a = a;
		LCD_PORT = 0x5F;			//	Low-Byte holen (ohne Bedeutung)									
		LCD_PORT = 0x4F;		
		}
	while ( (a1 & 0x08) != 0 ); //	warten solange Busy high										
 **** Ende Busy-Abfrage ******************************************************************/	
	
//	wenn Busy nicht geht, Zeitverzögerung 1ms verwenden ************************************/
	warte100u (10);
	}

/*****************************************************************************************
 * LCD-Cursor auf eine position setzen: Zeile 1: Adresse 0x00 bis 0x0F                     
 *                                      Zeile 2: Adresse 0x40 bis 0x4F  
 *                                      Zeile 3: Adresse 0x10 bis 0x1F
 *													 Zeile 4: Adresse 0x50 bis 0x5F                   
 ******************************************************************************************/
void cursorpos (unsigned char position)
	{ 	LCDbefehl ( position | 0x80 ); }	// Kennung für DD RAM address set							

/*****************************************************************************************
 * LCD-Cursor auf die Position Home 0x00 setzen                                           
 ******************************************************************************************/	
void cursorhome (void)
	{	LCDbefehl (0x02 );	}

/*****************************************************************************************
 * Text an das LCD-Display in Zeile 1 bsi 4 ausgeben                          
 ******************************************************************************************/
void textlcd (unsigned char *text, unsigned char zeile)									
	{
	if 	(zeile == 1)	{ cursorpos (0x00);}	//	LCD-Zeile 1											
	if    (zeile == 2) 	{ cursorpos (0x40);}	// LCD-Zeile 2			
	if		(zeile == 3)   { cursorpos (0x10);} // LCD-Zeile 3	
	if    (zeile == 4)   { cursorpos (0x50);} // LCD-Zeile 4   							
	while (*text)										//	Text													
		charlcd(*text++);                      //	zeichenweise ausgeben 							
	}

