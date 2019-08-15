package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;

import android.content.Context;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.AlphaAnimation;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import org.joda.time.DateTime;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.HC;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import se.emilsjolander.stickylistheaders.StickyListHeadersAdapter;
import se.emilsjolander.stickylistheaders.StickyListHeadersListView;

/**
 * Created by Modestas Valauskas on 05.06.2015.
 */
public class MyOverviewAdapter extends BaseAdapter implements StickyListHeadersAdapter {

    static class HeaderIndex {
        int headernumber;
        String typ;
        EintragObj eintragObj;

        public HeaderIndex(int headernumber, String typ, EintragObj eintragObj) {
            this.headernumber = headernumber;   // HEADER NUMBER #
            this.typ = typ; //art eintrag, neu, deleted...
            this.eintragObj = eintragObj;
        }
    }

    private LayoutInflater inflater;

    public String[] arten = {"eintrag","neu","deleted"};
    public ArrayList<HeaderIndex> neuOderEintrag = new ArrayList<>();

    public int tageAnzeigen = 15;
    public ArrayList<DateTime> datesToShow = new ArrayList<>();

    public ArrayList<Integer> deletedEintraegePerDate = new ArrayList<>();


    public MyOverviewAdapter(final Context context, StickyListHeadersListView list) {
        inflater = LayoutInflater.from(context);
    }

    public void populateArrays(EintraegeList eintraegeList) {

        //Comparator<EintragObj> comparator = new Comparator<EintragObj>() {
        //    public int compare(EintragObj o1, EintragObj o2) {
        //        if (o1.getDatum().toString("dd.MMMM.yyyy").equals(o2.getDatum().toString("dd.MMMM.yyyy"))) {
        //            return 0;
        //        }
        //        return o1.getDatum().getMillis() > o2.getDatum().getMillis() ? -1 : 1;
        //    }
        //};

        Comparator<DateTime> comparator2 = new Comparator<DateTime>() {
            public int compare(DateTime o1, DateTime o2) {
                if (o1.getMillis() == o2.getMillis()) {
                    return 0;
                }
                return o1.getMillis() > o2.getMillis() ? -1 : 1;
            }
        };


        // loop through every day that needs to be shown
        for(int i = 0; i < tageAnzeigen; i++) {

            //array mit Tagen die angezeigt werden müssen
            datesToShow.add(DateTime.now().withTime(0,0,0,0).plusDays(i));

        }

        Collections.sort(datesToShow, comparator2);


        ArrayList<EintragObj> newList = new ArrayList<>(eintraegeList);

        //RANDOM ORDER
        for (EintragObj eintragObj: newList) {

            //0 = kleinstes?
            if(eintragObj.getDatum().getMillis() >= datesToShow.get(0).getMillis()) {
                eintraegeList.remove(eintragObj);
            } else if(eintragObj.getDatum().getMillis() < datesToShow.get(datesToShow.size()-1).getMillis()) {
                eintraegeList.remove(eintragObj);
            }
        }
        Collections.reverse(datesToShow);

        int ijij = 0;
        for(DateTime dateToShow: datesToShow) {

            //einträge for that day
            ArrayList<EintragObj> tagEintraege = new ArrayList<>();

            //Counter for deleted Einträge
            Integer deletedOnThatDay = 0;

            //loop through einträge and add to day array
            for (EintragObj eintrag : eintraegeList) {
                if (eintrag.getDatum("dd.MMMM.yyyy").equals(dateToShow.toString("dd.MMMM.yyyy"))) {
                    if (eintrag.isDeletable()) {
                        tagEintraege.add(eintrag);
                    } else {
                        deletedOnThatDay++;
                    }
                }
            }

            //wieviele am jeweiligen Tag gelöscht
            deletedEintraegePerDate.add(deletedOnThatDay);

            final int size = tagEintraege.size();

            for(int j = 0; j < 2+size; j++) {
                if (j < size) {
                    neuOderEintrag.add(new HeaderIndex(ijij, arten[0], tagEintraege.get(j)));
                } else if( j == size) {
                    neuOderEintrag.add(new HeaderIndex(ijij, arten[1], null));
                } else if( j > size) {
                    neuOderEintrag.add(new HeaderIndex(ijij, arten[2], null));
                }

            }

            ijij++;
        }

    }



    public String getTyp(int position) {
        return neuOderEintrag.get(position).typ;
    }

    public int getHeaderNummer(int position) {
        return neuOderEintrag.get(position).headernumber;
    }

    public EintragObj getEintragObj(int position) {
        return neuOderEintrag.get(position).eintragObj;
    }

    @Override
    public int getCount() {
        return neuOderEintrag.size();
    }

    @Override
    public EintragObj getItem(int position) {
        return neuOderEintrag.get(position).eintragObj;
    }

    @Override
    public long getItemId(int position) {
        return 0;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (neuOderEintrag.get(position).typ.equals(arten[0])) {

            convertView = inflater.inflate(R.layout.overviewrow, parent, false);

            TextView fach = (TextView) convertView.findViewById(R.id.fach);
            TextView beschreibung = (TextView) convertView.findViewById(R.id.beschreibung);
            ImageView typ = (ImageView) convertView.findViewById(R.id.typ);
            beschreibung.setTextColor(Color.GRAY);

            EintragObj eintragObj = getItem(position);

            fach.setText(eintragObj.getName());
            beschreibung.setText(eintragObj.getBeschreibung());
            typ.setImageResource(eintragObj.getImage());


        } else if (neuOderEintrag.get(position).typ.equals(arten[1])) {

            convertView = inflater.inflate(R.layout.overviewrow, parent, false);

            TextView fach = (TextView) convertView.findViewById(R.id.fach);
            TextView beschreibung = (TextView) convertView.findViewById(R.id.beschreibung);
            ImageView typ = (ImageView) convertView.findViewById(R.id.typ);
            beschreibung.setTextColor(Color.GRAY);

            fach.setText("");
            beschreibung.setText("");
            typ.setImageResource(R.drawable.plus);


        } else {

            convertView = inflater.inflate(R.layout.overviewrowdeleted, parent, false);

            TextView beschreibung = (TextView) convertView.findViewById(R.id.beschreibung);
            ImageView typ = (ImageView) convertView.findViewById(R.id.typ);
            beschreibung.setTextColor(Color.GRAY);

            beschreibung.setText(deletedEintraegeText( deletedEintraegePerDate.get(neuOderEintrag.get(position).headernumber)));

            if (deletedEintraegePerDate.get(neuOderEintrag.get(position).headernumber) > 0) {
                beschreibung.setTextColor(Color.RED);
            }

            typ.setImageResource(R.drawable.trash);
        }

        return convertView;
    }

    public String deletedEintraegeText(int anzahl) {
        if(anzahl == 1) {
            return "1 Eintrag gelöscht";
        } else {
            return anzahl + " Einträge gelöscht";
        }
    }

    @Override
    public View getHeaderView(int position, View convertView, ViewGroup parent) {

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.overviewheader, parent, false);
        }

        TextView datum = (TextView) convertView.findViewById(R.id.headerDatum);
        datum.setText(datesToShow.get(neuOderEintrag.get(position).headernumber).toString("EE, d MMM yyyy"));
        return convertView;
    }

    @Override
    public long getHeaderId(int position) {
        return neuOderEintrag.get(position).headernumber;
    }
}

