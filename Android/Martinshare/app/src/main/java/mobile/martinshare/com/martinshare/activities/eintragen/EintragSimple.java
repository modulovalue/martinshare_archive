package mobile.martinshare.com.martinshare.activities.eintragen;

public class EintragSimple {
    private String art;
    private String fach;
    private String beschreibung;
    private String datum;
    private String id;
    private String deleted;

    public EintragSimple(String art, String fach, String beschreibung, String datum, String deleted) {
        this.art = art;
        this.fach = fach;
        this.beschreibung = beschreibung;
        this.datum = datum;
        id = null;
        this.deleted = deleted;
    }

    public EintragSimple(String fach, String beschreibung, String id, String datum, String art, String etwas, String deleted) {
        this.art = art;
        this.fach = fach;
        this.beschreibung = beschreibung;
        this.datum = datum;
        this.id = id;
        this.deleted = deleted;
    }

    public String getDatum() {
        return datum;
    }

    public String getBeschreibung() {
        return beschreibung;
    }

    public String getFach() {
        return fach;
    }

    public String getArt() {
        return art;
    }

    public boolean isDeletable() {
        return deleted.equals("0");
    }

    public boolean anythingNullandEmpty() {
        if (datum != null && fach != null && art != null &&
                !datum.isEmpty() && !fach.isEmpty() && !art.isEmpty()  && !deleted.isEmpty()) {
            return false;
        }
        return true;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }
}
