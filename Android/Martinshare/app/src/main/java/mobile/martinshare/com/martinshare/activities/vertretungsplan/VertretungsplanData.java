package mobile.martinshare.com.martinshare.activities.vertretungsplan;

import java.util.ArrayList;

/**
 * Created by Modestas Valauskas on 11.04.2015.
 */
public class VertretungsplanData {

    private ArrayList<String> urls = new ArrayList<>();
    private int countSeiten = 0;

    public VertretungsplanData() {

    }
    public int getCountSeiten() {
        return countSeiten;
    }

    public boolean gibtVertretungsplan() {
        return (countSeiten > 0);
    }

    public void setCountSeiten(int countSeiten) {
        this.countSeiten = countSeiten;
    }

    public ArrayList<String> getUrls() {
        return urls;
    }

    public void setUrls(ArrayList<String> urls) {
        this.urls = urls;
    }
}
