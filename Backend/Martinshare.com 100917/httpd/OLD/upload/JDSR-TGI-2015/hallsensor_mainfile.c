#include <at89c5131.h>
#include <lcd.c>
#include <i2c.c>

unsigned char digital_spannung = 0;
unsigned char digital_ampere = 0;
unsigned char buffer[16];
volatile int i;
bit failure = 0;

void Zeit (int k) {
    for(k; k>0; k--) {
        for (i = 50; i>0; i--) {
            _nop_();
}   }   }

void Ausgabe_LCD(void) {
    sprintf (buffer,"voltage    %03d V",digital_spannung);
    textlcd (buffer,1);

    sprintf (buffer,"ampere     %03d A",digital_ampere);
    textlcd (buffer,2);
}

void i2c_Batterie(void) {
//AMPERE einlesen
    i2c_start();
    i2c_schreiben(0x90);
    i2c_schreiben(0x01);
    i2c_stop();
    Zeit(50);
    i2c_start();
    i2c_schreiben(0x91);
    digital_ampere = i2c_lesen(1);
    digital_ampere = i2c_lesen(0);
    i2c_stop();
//--------------------------------

    Zeit(50);

//VOLTAGE einlesen
    i2c_start();
    i2c_schreiben(0x90);
    i2c_schreiben(0x00);
    i2c_stop();
    Zeit(50);
    i2c_start();
    i2c_schreiben(0x91);
    digital_spannung = i2c_lesen(1);
    digital_spannung = i2c_lesen(0);
    i2c_stop();
//--------------------------------

//BERECHNUNG
    if (digital_ampere > 149) {failure = 1;} // Mehr als 22 Ampere auf H-Brdg
}

void main(void) {
    initlcd();
    i2c_init();

    while(1) {
       i2c_Batterie();
       Zeit(100);
       Ausgabe_LCD(); 
    }
}