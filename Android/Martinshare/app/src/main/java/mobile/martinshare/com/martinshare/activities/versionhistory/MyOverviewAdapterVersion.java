package mobile.martinshare.com.martinshare.activities.versionhistory;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import org.joda.time.DateTime;

import java.util.ArrayList;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import se.emilsjolander.stickylistheaders.StickyListHeadersAdapter;
import se.emilsjolander.stickylistheaders.StickyListHeadersListView;

/**
 * Created by Modestas Valauskas on 05.06.2015.
 */
class MyOverviewAdapterVersion extends BaseAdapter implements StickyListHeadersAdapter {


    private LayoutInflater inflater;

    public EintraegeList eintraege;


    public MyOverviewAdapterVersion(final Context context, EintraegeList eintraegeList, StickyListHeadersListView list) {
        eintraege = eintraegeList;
        inflater = LayoutInflater.from(context);
    }

    @Override
    public int getCount() {
        return eintraege.size();
    }

    @Override
    public EintragObj getItem(int position) {
        return eintraege.get(position);
    }

    @Override
    public long getItemId(int position) {
        return 0;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        convertView = inflater.inflate(R.layout.overviewrow, parent, false);

        TextView fach = (TextView) convertView.findViewById(R.id.fach);
        TextView beschreibung = (TextView) convertView.findViewById(R.id.beschreibung);
        ImageView typ = (ImageView) convertView.findViewById(R.id.typ);
        beschreibung.setTextColor(Color.GRAY);

        EintragObj eintragObj = getItem(position);

        fach.setText(eintragObj.getName());
        beschreibung.setText(eintragObj.getBeschreibung());
        typ.setImageResource(eintragObj.getImage());

        return convertView;

    }


    @Override
    public View getHeaderView(int position, View convertView, ViewGroup parent) {

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.overviewheader, parent, false);
        }

        TextView datum = (TextView) convertView.findViewById(R.id.headerDatum);
        datum.setText(eintraege.get(position).getErstellDatum().toString("EEEE, dd. MMM yyyy HH:mm:ss"));
        return convertView;
    }

    @Override
    public long getHeaderId(int position) {
        return position;
    }
}

