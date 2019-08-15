package mobile.martinshare.com.martinshare.activities.MainView;


import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.ListFragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.API.protocols.GetActivityProtocol;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivityListener;

public class SampleListFragment extends ListFragment {

    SwipeRefreshLayout refreshLayout;

    TextView aktivitaetTitle;

    EintraegeList eintraegeList;

    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.list, null);
        aktivitaetTitle = (TextView) view.findViewById(R.id.aktivitaettitle);

        refreshLayout = (SwipeRefreshLayout) view.findViewById(R.id.aktivitaetrefreshlayout);
        refreshLayout.setColorSchemeResources(android.R.color.holo_red_light);
        refreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                MartinshareApiRetro.getActivität(getActivity(), getActivityProtocol, false);
            }
        });

        return view;
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);

        ((MainActivity) context).menu.setOnOpenListener(new SlidingMenu.OnOpenListener() {
            @Override
            public void onOpen() {
                MartinshareApiRetro.getActivität(getActivity(), getActivityProtocol, false);
            }
        });

    }

    GetActivityProtocol getActivityProtocol = new GetActivityProtocol() {

        @Override
        public void startedGetting() {
            refreshLayout.setRefreshing(true);
            aktivitaetTitle.setText("Bitte warten");
        }

        @Override
        public void notLoggedIn() {
            refreshLayout.setRefreshing(false);
            aktivitaetTitle.setText("Du bist nicht eingeloggt");
        }

        @Override
        public void noInternet() {
            refreshLayout.setRefreshing(false);
            aktivitaetTitle.setText("Keine Verbindung");
        }

        @Override
        public void unknownError() {
            refreshLayout.setRefreshing(false);
            aktivitaetTitle.setText("Unbekannter Fehler");
        }


        @Override
        public void aktualisiert(boolean warn, String fetchedData) {

            try {

                eintraegeList = new EintraegeList();
                SampleAdapter adapter = new SampleAdapter();

                final JSONArray array = new JSONArray(fetchedData);
                for (int i = 0; i < array.length(); i++) {

                    JSONObject row = array.getJSONObject(i);

                    if(row.getString("atype").equals("show")) {
                        EintragObj eintragObj = new EintragObj(
                                row.getString("id"),
                                row.getString("name"),
                                row.getString("beschreibung"),
                                row.getString("typ"),
                                row.getString("datum"),
                                row.getString("erstelldatum"),
                                row.getString("deleted"),
                                row.getString("version"));

                        eintraegeList.add(eintragObj);

                        adapter.add(new SampleItem(eintragObj,
                                row.getString("titlestyle"),
                                row.getString("acontent"),
                                row.getString("atype"),
                                row.getInt("vortimestamp")));
                    }

                }

                setListAdapter(adapter);
                adapter.notifyDataSetChanged();
                aktivitaetTitle.setText("Ereignisse");
            } catch (JSONException e) {
                aktivitaetTitle.setText("Fehler beim Laden der Informationen");
                e.printStackTrace();
            };

            refreshLayout.setRefreshing(false);
        }
    };

    private class SampleItem {
        final String atype;
        EintragObj eintragObj;
        String titlestyle;
        String acontent;
        int vortimestamp = 0;

        public SampleItem(EintragObj eintragObj, String titlestyle, String acontent, String atype, int vortimestamp) {
            this.eintragObj = eintragObj;
            this.titlestyle = titlestyle;
            this.atype = atype;
            this.acontent = acontent;
            this.vortimestamp = vortimestamp;
        }

    }

    public class SampleAdapter extends ArrayAdapter<SampleItem> {

        public SampleAdapter() {
            super(getActivity(), 0);
        }

        public View getView(int position, View convertView, ViewGroup parent) {
            if (convertView == null) {
                convertView = LayoutInflater.from(getContext()).inflate(R.layout.row, null);
            }

            if (getItem(position).atype.equals("show")) {
                ImageView icon = (ImageView) convertView.findViewById(R.id.typeicon);
                icon.setImageResource(R.drawable.calendar1);

                ImageView icon3 = (ImageView) convertView.findViewById(R.id.typeicon3);
                icon3.setImageResource(getItem(position).eintragObj.getImage());
            }

            ImageView icon2 = (ImageView) convertView.findViewById(R.id.typeicon2);
            if(getItem(position).titlestyle.equals("deleted")) {
                icon2.setImageResource(R.drawable.delete);
            } else if(getItem(position).titlestyle.equals("new")) {
                icon2.setVisibility(View.GONE);
            } else if(getItem(position).titlestyle.equals("update")) {
                icon2.setImageResource(R.drawable.refresh1);
            }


            EintragObj e = getItem(position).eintragObj;
            TextView title = (TextView) convertView.findViewById(R.id.row_title);
            title.setText(e.getName());

            TextView titleup = (TextView) convertView.findViewById(R.id.typeicontext);
            titleup.setText(getVorTimeString(getItem(position).vortimestamp));

            TextView message = (TextView) convertView.findViewById(R.id.row_message);
            message.setText((e.getBeschreibung().equals("") ? "Keine Beschreibung vorhanden" : e.getBeschreibung()));

            TextView subtitle = (TextView) convertView.findViewById(R.id.row_submessage);
            subtitle.setText(e.getBeauDatum());

            return convertView;
        }


    }

    public String getVorTimeString(int vortimestamp) {
        Float timestamp = (float) vortimestamp;
        if(timestamp / 60f < 1) {
            return "vor " + Math.round(timestamp/1) + " " + ((Math.round(timestamp / 1)) == 1 ? "Sekunde" : "Sekunden");
        } else if( timestamp / 3600f < 1) {
            return "vor " + Math.round(timestamp/60) + " " +  ((Math.round(timestamp / 60)) == 1 ? "Minute" : "Minuten");
        } else if( timestamp / 86400 < 1) {
            return "vor " + Math.round(timestamp/3600) + " " +  ((Math.round(timestamp / 3600)) == 1 ? "Stunde" : "Stunden");
        } else {
            return "vor " + Math.round(timestamp/86400) + " " +  ((Math.round(timestamp / 86400)) == 1 ? "Tag" : "Tagen");
        }
    }


    @Override
    public void onListItemClick(ListView lv, View v, int position, long id) {
        ((MainActivity) getActivity()).showOverviewFragment(eintraegeList.get(position));
    }

}
