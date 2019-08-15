package mobile.martinshare.com.martinshare.activities.MainView.UebersichtTab;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;

import org.joda.time.DateTime;

import butterknife.Bind;
import butterknife.ButterKnife;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;

import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.PushStuff.GcmListenerService;

import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.KalenderTab.KalenderTab;
import mobile.martinshare.com.martinshare.activities.MainView.KalenderTab.MyOverviewAdapter;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.eintragen.Eintragen;
import se.emilsjolander.stickylistheaders.StickyListHeadersListView;

/**
 * Created by Modestas Valauskas on 08.02.2016.
 */
public class UebersichtTab extends Fragment {

    public static boolean refresh = true;

    @Bind(R.id.listuebersicht)
    StickyListHeadersListView list;

    @Bind(R.id.pinned_lisview_container)
    public SwipeRefreshLayout refreshLayout;

    @Override
    public void onResume() {
        super.onResume();

    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.tab_uebersicht, container, false);

        ButterKnife.bind(this, view);

        refreshLayout.setColorSchemeResources(android.R.color.holo_red_light);
        refreshLayout.setRefreshing(false);
        refreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                MartinshareApiRetro.downloadEintraege(getActivity(), ((MainActivity) getActivity()).getEintraegeProtocol, false);
            }
        });

        if(GcmListenerService.shouldAktualisierenMainActivity) {
            MartinshareApiRetro.downloadEintraege(getActivity(), ((MainActivity) getActivity()).getEintraegeProtocol, true);
            GcmListenerService.shouldAktualisierenMainActivity = false;
        } else {
            if(refresh == false) {
                loadUbersicht();
                refresh = false;
            }
        }

        return view;
    }

    public void loadUbersicht() {
        new LoadUber().execute();
    }

    private class LoadUber extends AsyncTask<Void, Void, Void> {

        SweetAlertDialog pDialog;
        MyOverviewAdapter adapter = new MyOverviewAdapter(getActivity(), list);
        AdapterView.OnItemClickListener clickListener;
        @Override
        protected void onPreExecute() {
            pDialog = new SweetAlertDialog(getActivity(), SweetAlertDialog.PROGRESS_TYPE);
            pDialog.setTitleText("Bitte warten");
            pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
            pDialog.setCancelable(false);
            pDialog.show();

            refreshLayout.setRefreshing(false);
        }

        @Override
        protected Void doInBackground(Void... params) {

            final EintraegeList eintraegeOrig = Prefs.getEintrags(getActivity());

            adapter.populateArrays(eintraegeOrig);

            clickListener = new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                    if (adapter.getTyp(position).equals(adapter.arten[1])) {
                        //Neuer Eintrag
                        final DateTime zeit = adapter.datesToShow.get(adapter.getHeaderNummer(position));

                        final String datumISOSQL = zeit.toString("yyyy-MM-dd");
                        final String anzeigeDatum = zeit.toString("EE, d MMMM yyyy");

                        Intent eintragIntent = new Intent(getActivity(), Eintragen.class);
                        eintragIntent.putExtra("SQLDatum", datumISOSQL);
                        eintragIntent.putExtra("anzeigeDatum", anzeigeDatum);
                        eintragIntent.putExtra("typ", "h");
                        startActivityForResult(eintragIntent, 0);

                    } else if(adapter.getTyp(position).equals(adapter.arten[0])) {
                        ((MainActivity) getActivity()).showOverviewFragment(adapter.getEintragObj(position));
                    } else {
                        KalenderTab.SHOWTHATTIME = adapter.datesToShow.get(adapter.getHeaderNummer(position));
                        ((MainActivity) getActivity()).viewPager.setCurrentItem(1, true);
                    }
                }
            };

            return null;
        }


        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            list.setAdapter(adapter);
            list.setOnItemClickListener(clickListener);
            pDialog.dismiss();
            pDialog = null;
            refresh = false;
        }

    }

}