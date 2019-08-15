package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;
import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.content.ContextCompat;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.melnykov.fab.ObservableScrollView;

import org.joda.time.DateTime;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import butterknife.Bind;
import butterknife.ButterKnife;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.HC;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;

/**
 * Created by Modestas Valauskas on 08.02.2016.
 */

interface CalendarDayInterface {
    Drawable markImage(DateTime date);
    void handleDayClick(DateTime date, CalendarDayFragment calendarDayFragment, boolean startOfMonth);
    boolean isToday(DateTime Date);
}

public class KalenderTab extends Fragment implements CalendarDayInterface {


    public static boolean refresh = true;
    public DateTime selectedDate;
    public CalendarZeit calendarZeit = CalendarZeit.getInstance();
    public CalendarDayFragment[] cdf = new CalendarDayFragment[43];

    @Bind(R.id.eintrag_layout_container)
    public LinearLayout eintraglayoutcontainer;

    @Bind(R.id.eintrag_view)
    public ObservableScrollView eintraglistview;

    private TextView[] weekDays = new TextView[7];

    public static DateTime SHOWTHATTIME = null;

    public Map<String, Integer> dateShowMarker = new HashMap<>();

    private int sbit = 1;
    private int abit = 2;
    private int hbit = 4;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.tab_kalender, container, false);

        System.err.println("oncreateview start ------------------------------");
        ButterKnife.bind(this, view);

        eintraglistview.setSmoothScrollingEnabled(true);

        for(int i = 1; i <= 7; i++) {
            weekDays[i-1] = (TextView) view.findViewById(getResources().getIdentifier("wd" + i, "id", getActivity().getPackageName()));
            weekDays[i-1].setTextSize(12);
        }

        for(int i = 1; i <= 42; i++) {
            cdf[i] = (CalendarDayFragment) getChildFragmentManager().findFragmentById(getResources().getIdentifier("caldayfrag" + i, "id", getActivity().getPackageName()));
        }

        if(refresh == true) {
            new LoadKal().execute();
            refresh = false;
        }

        System.err.println("onCreateVIew end ------------------------------");
        return view;
    }

    public void loadKalender() {
        new LoadKal().execute();
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    private class LoadKal extends AsyncTask<Void, Void, Void> {

        SweetAlertDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new SweetAlertDialog(getActivity(), SweetAlertDialog.PROGRESS_TYPE);
            pDialog.setTitleText("Bitte warten");
            pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
            pDialog.setCancelable(false);
            pDialog.show();

        }

        @Override
        protected Void doInBackground(Void... params) {
            DateTime now;

            dateShowMarker.clear();
            dateShowMarker = populateDateShowMarker();

            if(SHOWTHATTIME == null) {
                now = DateTime.now().withTime(0, 0, 0, 0);
                calendarZeit.resetTime();
            } else {
                now = SHOWTHATTIME.withTime(0,0,0,0);
                //calendarZeit.dt = SHOWTHATTIME;
                SHOWTHATTIME = null;
            }

            handleDayClick(now, null, false);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            pDialog.dismissWithAnimation();
            pDialog = null;
        }

    }

    public Map<String, Integer> populateDateShowMarker() {

        Map<String, Integer> datesm = new HashMap<>();

        EintraegeList eintraege = Prefs.getEintrags(getActivity());

        if( !eintraege.isEmpty())
        for (EintragObj eintrag : eintraege) {

            if(datesm.get(eintrag.datum) == null) {
                datesm.put(eintrag.datum, 0);
            }

            switch (eintrag.getTyp()) {
                case "s":
                    datesm.put(eintrag.datum, datesm.get(eintrag.datum) | sbit);
                    break;
                case "a":
                    datesm.put(eintrag.datum, datesm.get(eintrag.datum) | abit);
                    break;
                case "h":
                    datesm.put(eintrag.datum, datesm.get(eintrag.datum) | hbit);
                    break;
                case "f":
                    datesm.put(eintrag.datum, datesm.get(eintrag.datum) | sbit);
                    break;
            }

        }

        return datesm;
    }


    public void populateDaysWithDates() {

        boolean prevmonth = false;
        boolean thismonth = false;
        boolean nextmonth = false;

        DateTime dati = selectedDate.withDayOfMonth(1).withDayOfWeek(1);

        for( int i = 1 ; i <= 42 ; i++ ) {

            DateTime firstOfMonth = dati.plusDays(i - 1);

            if (!prevmonth && !thismonth && !nextmonth && firstOfMonth.getDayOfMonth() == 1 ){
                thismonth = true;
            } else {
                if(!prevmonth && !thismonth && !nextmonth && firstOfMonth.getDayOfMonth() > 1 ){
                    prevmonth = true;
                } else if(!prevmonth && thismonth && firstOfMonth.getDayOfMonth() == 1) {
                    thismonth = false;
                    nextmonth = true;
                } else if(prevmonth && firstOfMonth.getDayOfMonth() == 1) {
                    thismonth = true;
                    prevmonth = false;
                }
            }

            if (thismonth) {
                cdf[i].setDate(firstOfMonth, true, false, false);
            } else if (prevmonth) {
                DateTime prevDate = firstOfMonth.minusDays(0);
                cdf[i].setDate(prevDate, false, false, true);
            } else if (nextmonth) {
                DateTime nextDate = firstOfMonth.plusDays(0);
                cdf[i].setDate(nextDate, false, true , false);
            }

            cdf[i].setText(String.valueOf(cdf[i].date.getDayOfMonth()));
        }

    }


    public Map<String, Integer> getDateShowMarker() {
        if(dateShowMarker.isEmpty()) {
            dateShowMarker.clear();
            dateShowMarker = populateDateShowMarker();
        }
        return dateShowMarker;
    }

    @Override
    public Drawable markImage(DateTime date) {

        if(getDateShowMarker().get(date.getYear() + "-" + HC.addlead0(date.getMonthOfYear()) + "-" + HC.addlead0(date.getDayOfMonth())) == null) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarker);
        }

        int dateStringImageInt = getDateShowMarker().get(date.getYear() + "-" + HC.addlead0(date.getMonthOfYear()) + "-" + HC.addlead0(date.getDayOfMonth()));

        if(dateStringImageInt == 0 ) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarker);
        } else if(dateStringImageInt == 1) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkers);
        } else if(dateStringImageInt == 2) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkera);
        } else if(dateStringImageInt == 3) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkeras);
        } else if(dateStringImageInt == 4) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkerh);
        } else if(dateStringImageInt == 5) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkerhs);
        } else if(dateStringImageInt == 6) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkerha);
        } else if(dateStringImageInt == 7) {
            return ContextCompat.getDrawable(getActivity(), R.drawable.calmarkerhas);
        } else {
            return null;
        }

    }

    @Override
    public boolean isToday(DateTime date) {
        return date.withTime(0, 0, 0, 0).equals(DateTime.now().withTime(0, 0, 0, 0));
    }

    @Override
    public void handleDayClick(final DateTime date, CalendarDayFragment calendarDayFragment, final boolean startOfMonth) {

        if(startOfMonth) {
            if(date.getYear() == DateTime.now().getYear() && date.getMonthOfYear() == DateTime.now().getMonthOfYear()) {
                selectedDate = date.withDayOfMonth(DateTime.now().getDayOfMonth());
            } else {
                selectedDate = date.withDayOfMonth(1);
            }
        } else {
            selectedDate = date;
        }

        populateDaysWithDates();

        //reset Days
        for( int i = 1 ; i <= 42 ; i++ ) {
            cdf[i].resetDay(selectedDate);
        }

        //remove alle Einträge
        if(eintraglayoutcontainer.getChildCount() > 0) {
            getActivity().runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    eintraglayoutcontainer.removeAllViews();
                }
            });
        }

        final ArrayList<EintragObj> eintraege = Prefs.getEintrags(getActivity().getApplicationContext());
        final ArrayList<EintragObj> normal = new ArrayList<>();
        final ArrayList<EintragObj> deleted = new ArrayList<>();


        for(int i = 0; i < eintraege.size(); i++ ) {
            if(eintraege.get(i).getDatum().isEqual(selectedDate.withTime(0,0,0,0)) ) {

                final CalendarEintrag calein1 = new CalendarEintrag();

                Bundle bundle = new Bundle();
                bundle.putParcelable("eintrag", eintraege.get(i));
                calein1.setArguments(bundle);


                final int finalI = i;
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        FragmentTransaction fragmentTransaction = getActivity().getSupportFragmentManager().beginTransaction();
                        fragmentTransaction.add(R.id.eintrag_layout_container, calein1, eintraege.get(finalI).getId());
                        fragmentTransaction.commit();

                        if (eintraege.get(finalI).isDeletable()) {
                            normal.add(eintraege.get(finalI));
                        } else {
                            deleted.add(eintraege.get(finalI));
                        }
                    }
                });



            }
        }

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                FragmentTransaction fragmentTransaction = getActivity().getSupportFragmentManager().beginTransaction();
                NeuerEintragMOD mod = NeuerEintragMOD.newInstance(selectedDate.toString("EEEE, d MMMM yyyy"), selectedDate.toString("yyyy-MM-dd"));
                fragmentTransaction.setCustomAnimations(R.anim.push_left_in, 0, 0, R.anim.push_right_out);
                fragmentTransaction.add(R.id.eintrag_layout_container, mod, "neuerEintrag");
                fragmentTransaction.commit();

                getActivity().supportInvalidateOptionsMenu();
            }
        });
    }

}

