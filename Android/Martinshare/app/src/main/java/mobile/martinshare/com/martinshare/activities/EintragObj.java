package mobile.martinshare.com.martinshare.activities;

import android.os.Bundle;
import android.os.Parcel;
import android.os.Parcelable;
import android.text.Html;
import mobile.martinshare.com.martinshare.MyApplication;
import mobile.martinshare.com.martinshare.R;
import org.joda.time.DateTime;

import java.io.Serializable;
import java.util.Objects;

/**
 * Created by Modestas Valauskas on 07.04.2015.
 */
public class EintragObj implements Parcelable, Serializable {

    private String id;
    private String typ;
    private String name;
    private String beschreibung;
    public  String datum;
    private String erstelldatum;
    private String deleted;
    private String version;
    private DateTime bisDatum;
    private DateTime erstellDatum;


    public EintragObj(String id, String name, String beschreibung, String typ, String datum, String erstelldatum, String deleted, String version) {
        this.id = id;
        this.name = name;
        this.beschreibung = beschreibung;
        this.typ = typ;
        this.datum = datum;
        this.erstelldatum = erstelldatum;
        this.deleted = deleted;
        this.version = version;
    }

    public int getImage() {
        switch (typ) {
            case "h":
                return R.drawable.ic_hicon;
            case "a":
                return R.drawable.ic_aicon;
            case "s":
                return R.drawable.ic_sicon;
            default:
                return R.drawable.ic_sicon;
        }
    }

    public String getBeauDatum() {
        return getDatum().toString("EEEE, dd MMMM yyyy");
    }


    public String datumToString() {
        return getDatum().getDayOfMonth() +"."+ getDatum().getMonthOfYear() +"."+ getDatum().getYear();
    }

    public String toString() {
        return getTypAusgeschrieben() + ": " + getBeauDatum() + " \nFach: " + getName() + " \nBeschreibung: "+ getBeschreibung();
    }

    public String getId() {
        return id;
    }

    public static String getBRedStringFromHtml(String string) {
        if(!string.equals("")) {
            String str = string.replace("\n", "<br>");
            return Html.fromHtml(str).toString();
        } else {
            return string;
        }
    }

    public String getName() {
        return EintragObj.getBRedStringFromHtml(name);
    }

    public String getBeschreibung() {
        return EintragObj.getBRedStringFromHtml(beschreibung);
    }

    public String getTypAusgeschrieben() {
        switch (typ) {
            case "h":
                return "Hausaufgabe";
            case "a":
                return "Arbeitstermin";
            case "s":
                return "Sonstiges";
        }
        return "Error";
    }

    public DateTime getDatum() {
        if(bisDatum == null) {
            String[] datumSplitted = datum.split("-");
            int day = Integer.parseInt(datumSplitted[2]);
            int month = Integer.parseInt(datumSplitted[1]);
            int year = Integer.parseInt(datumSplitted[0]);
            this.bisDatum = DateTime.now().withDate(year,month,day).withTime(0,0,0,0);
        }
        return bisDatum;
    }
    public String getDatum(String string) {
        return getDatum().toString(string);
    }
    public boolean isDeletable() {
        return deleted.equals("0");
    }

    public boolean firstVersion() {
        return version.equals("1");
    }

    public DateTime getErstellDatum() {
        if(erstellDatum == null ) {
            String[] erstelldDatumSplitted = erstelldatum.split("-| |:");
            int erY = Integer.parseInt(erstelldDatumSplitted[0]);
            int erM = Integer.parseInt(erstelldDatumSplitted[1]);
            int erD = Integer.parseInt(erstelldDatumSplitted[2]);
            int erH = Integer.parseInt(erstelldDatumSplitted[3]);
            int erMIN = Integer.parseInt(erstelldDatumSplitted[4]);
            int erSEC = Integer.parseInt(erstelldDatumSplitted[5]);
            this.erstellDatum = new DateTime(erY, erM, erD, erH, erMIN, erSEC, 0);
        }
        return erstellDatum;
    }

    public String getTyp() {
        return typ;
    }


    public EintragObj(Parcel in){
        String[] data = new String[8];

        in.readStringArray(data);

        this.id = data[0];
        this.name = data[1];
        this.beschreibung = data[2];
        this.typ = data[3];
        this.datum = data[4];
        this.erstelldatum = data[5];
        this.deleted = data[6];
        this.version = data[7];
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeStringArray(
                new String[] {

                this.id,
                this.name,
                this.beschreibung,
                this.typ,
                this.datum,
                this.erstelldatum,
                this.deleted,
                this.version});
    }

    public static final Parcelable.Creator CREATOR = new Parcelable.Creator() {
        public EintragObj createFromParcel(Parcel in) {
            return new EintragObj(in);
        }

        public EintragObj[] newArray(int size) {
            return new EintragObj[size];
        }
    };

    public String getVersion() {
        return version;
    }
}
