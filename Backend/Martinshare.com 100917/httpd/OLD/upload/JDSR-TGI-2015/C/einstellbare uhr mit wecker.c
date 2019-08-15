#include <at89c5131.h>
#include <stdio.h>
#include <lcd.c>

#define IRinput P3_3;

sfr at P2 LED;

unsigned char buffer[16];
unsigned char RC5;
unsigned int j, tcount;
unsigned char zehner, einser, stunde, minute, sekunde;
unsigned char weckerStunde, weckerMinute;
unsigned char Taster = 1;

unsigned char weckerKlingelt(void)
{
    return (weckerStunde == stunde) && 
           (weckerMinute == minute) && 
           (sekunde<=30);
}

void isr_taste (void) interrupt 0 {
    Taster = 0;
}

void timer (void) interrupt 5 {
    TF2 = 0;
    tcount++;

    if (tcount == 20) {
        tcount = 0;
        sekunde++;
    }

    if (weckerKlingelt())
    {LED = !LED;}
}

void delay (int k) {
    for(k; k!=0; k--);
}

void ISR_infra (void) interrupt 2 {
    EX1 = 0;
    RC5 = 0x00;
    delay(1710);

    for (j=0; j<6; j++) {
        RC5 <<= 1;
        RC5 = RC5|=IRinput;
        delay(216);
    }

    EX1 = 1;
}

void Ausgabe_LCD(void) {
    sprintf (buffer,"Ihre Auswahl:%2x",RC5);
    textlcd (buffer,2);
}

void Uhr_Stellen (void) {
    sprintf (buffer,"  Stunde 10er   ");
    textlcd (buffer,1);

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {zehner = RC5;}
    }

    Taster = 1; 
    delay(50000);

    sprintf (buffer,"  Stunde 1er    ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {einser = RC5;}
    }

    Taster = 1;
    stunde = zehner*10+einser;
    delay(50000);

    sprintf (buffer,"  Minute 10er   ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {zehner = RC5;}
    }

    Taster = 1; 
    delay(50000);

    sprintf (buffer,"  Minute 1er    ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {einser = RC5;}
    }

    minute = zehner*10+einser;
    delay(50000);

    sprintf (buffer,"  Sekunde 10er  ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {zehner = RC5;}
    }

    Taster = 1;
    delay(50000);

    sprintf (buffer,"  Sekunde 1er   ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {einser = RC5;}
    }

    Taster = 1;
    sekunde = zehner*10+einser;
    RC5 = 0x00;
    delay(50000);
}

void Wecker_Stellen( void )
{
    RC5 = 0;

    sprintf (buffer,"Wecker Std. 10er   ");
    textlcd (buffer,1);

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {zehner = RC5;}
    }

    Taster = 1; 
    delay(50000);

    sprintf (buffer,"Wecker Std.  1er   ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {einser = RC5;}
    }

    Taster = 1;
    weckerStunde = zehner*10+einser;
    delay(50000);

    sprintf (buffer,"Wecker Min. 10er   ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {zehner = RC5;}
    }

    Taster = 1; 
    delay(50000);

    sprintf (buffer,"Wecker Min.  1er   ");
    textlcd (buffer,1);
    RC5 = 0x00;

    while (Taster && (RC5!=0xd)) {
        Ausgabe_LCD();

        if (RC5 != 0xd)
        {einser = RC5;}
    }

    weckerMinute = zehner*10+einser;
    delay(50000);}

void Uhr (void) {
    if (sekunde == 60) {
        sekunde = 0;
        minute++;
    }

    if (minute == 60) {
        minute = 0;
        stunde++;
    }

    if (stunde == 24) {
        stunde = 0;
        minute = 0;
        sekunde = 0;
    }
}

void main (void) {
    LED = 0xA0;

    T2CON = 0x04;

    ET2 = 1;
    RCAP2L = 0xAF;
    RCAP2H = 0x3C;

    IPH0 = 0x04;
    IPL0 = 0x04;

    IT0 = 1;
    IT1 = 0;
    EX0 = 1;
    EX1 = 1;

    EA = 1;

    initlcd();

    Uhr_Stellen();
    Wecker_Stellen();

    sprintf (buffer,"martinshare.com ");
    textlcd (buffer,1);
    
while (1) {

    if (tcount <= 10) {
        sprintf (buffer,"    %02d:%02d:%02d    ",stunde,minute,sekunde);
        textlcd (buffer,2);
    }

    else {
        sprintf (buffer,"    %02d %02d %02d    ",stunde,minute,sekunde);
        textlcd (buffer,2);
    }
    Uhr();
}
}