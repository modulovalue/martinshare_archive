package mobile.martinshare.com.martinshare.activities.MainView;

import android.app.AlarmManager;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.pdf.PdfDocument;
import android.media.RingtoneManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.SystemClock;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.app.NotificationCompat;
import android.support.v7.app.ActionBar;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;

import butterknife.Bind;
import butterknife.ButterKnife;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.*;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.protocols.GetEintraegeProtocol;
import mobile.martinshare.com.martinshare.PushStuff.GcmListenerService;
import mobile.martinshare.com.martinshare.PushStuff.PushManager;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.MainView.EinstellungenTab.AppPreference;
import mobile.martinshare.com.martinshare.activities.MainView.EinstellungenTab.EinstellungenTab;
import mobile.martinshare.com.martinshare.activities.MainView.UebersichtTab.UebersichtTab;
import mobile.martinshare.com.martinshare.activities.MainView.KalenderTab.KalenderTab;
import mobile.martinshare.com.martinshare.activities.MainView.PlaeneTab.PlaeneTab;


import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.view.ViewPager;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu;

import org.joda.time.DateTime;

import java.text.DateFormatSymbols;

public class MainActivity extends AppCompatActivity {

    @Bind(R.id.tabs)
    public TabLayout tabLayout;

    @Bind(R.id.viewpager)
    public ViewPager viewPager;

    public static int AKTUALISIERENINTENT = 1234;

    BroadcastReceiver brcvr;

    SweetAlertDialog pDialog;

    public SlidingMenu menu;

    DialogFragment newFragment;

    private Toast mToast;

    public  GetEintraegeProtocol getEintraegeProtocol;

    public MainActivity() {

        UebersichtTab.refresh = true;
        KalenderTab.refresh = true;

    }

    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main2);
        ButterKnife.bind(this);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        mToast = Toast.makeText(this, "", Toast.LENGTH_SHORT);



        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setTitle("Übersicht");
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setSubtitle(String.valueOf(Prefs.getUsername(this)));
        }



        getEintraegeProtocol = new GetEintraegeProtocol() {

            @Override
            public void startedGetting() {
                runOnUiThread(new Runnable() {
                    public void run() {
                        pDialog.setTitleText("Bitte warten");
                        pDialog.show();
                    }
                });
            }

            @Override
            public void notLoggedIn() {

                runOnUiThread(new Runnable() {
                    public void run() {
                        Toast.makeText(MainActivity.this, "Du bist nicht eingeloggt", Toast.LENGTH_SHORT).show();
                        pDialog.hide();
                    }
                });

                switch (viewPager.getCurrentItem()) {
                    case 0:
                        UebersichtTab uebersichtTab = (UebersichtTab) viewPager.getAdapter().instantiateItem(viewPager, 0);
                        uebersichtTab.loadUbersicht();
                        break;
                    case 1:
                        KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                        kalenderTab.loadKalender();
                        break;
                }
            }

            @Override
            public void noInternet() {

                runOnUiThread(new Runnable() {
                    public void run() {
                        Toast.makeText(MainActivity.this,"Keine Internetverbindung", Toast.LENGTH_SHORT).show();
                        pDialog.hide();
                    }
                });

                switch (viewPager.getCurrentItem()) {
                    case 0:
                        UebersichtTab uebersichtTab = (UebersichtTab) viewPager.getAdapter().instantiateItem(viewPager, 0);
                        uebersichtTab.loadUbersicht();
                        break;
                    case 1:
                        KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                        kalenderTab.loadKalender();
                        break;
                }
            }

            @Override
            public void notChanged(final boolean warn) {
                runOnUiThread(new Runnable() {
                    public void run() {
                        Toast.makeText(MainActivity.this, "Einträge aktuell", Toast.LENGTH_SHORT).show();
                        pDialog.hide();
                    }
                });
                switch (viewPager.getCurrentItem()) {
                    case 0:
                        UebersichtTab uebersichtTab = (UebersichtTab) viewPager.getAdapter().instantiateItem(viewPager, 0);
                        uebersichtTab.loadUbersicht();
                        break;
                    case 1:
                        KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                        kalenderTab.loadKalender();
                        break;
                }
            }

            @Override
            public void aktualisiert(final boolean warn) {

                runOnUiThread(new Runnable() {
                    public void run() {
                        Toast.makeText(MainActivity.this, "Einträge wurden aktualisiert", Toast.LENGTH_SHORT).show();
                        pDialog.hide();
                    }
                });
                switch (viewPager.getCurrentItem()) {
                    case 0:
                        UebersichtTab uebersichtTab = (UebersichtTab) viewPager.getAdapter().instantiateItem(viewPager, 0);
                        uebersichtTab.loadUbersicht();
                        break;
                    case 1:
                        KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                        kalenderTab.loadKalender();
                        break;
                }
            }

        };
        loadMain();
    }

    public void loadMain() {
        new LoadMain().execute();
    }

    private class LoadMain extends AsyncTask<Void, Void, Void> {

        SweetAlertDialog sweetAlertDialog;
        PagerAdapter adapter;
        ViewPager.OnPageChangeListener listener;
        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            sweetAlertDialog = new SweetAlertDialog(MainActivity.this, SweetAlertDialog.PROGRESS_TYPE);
            sweetAlertDialog.setTitleText("Bitte warten");
            sweetAlertDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
            sweetAlertDialog.setCancelable(false);
            sweetAlertDialog.show();

        }

        @Override
        protected Void doInBackground(Void... params) {


            adapter = new PagerAdapter(getSupportFragmentManager(), 4);

            listener = new ViewPager.OnPageChangeListener() {

                @Override
                public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {
                }

                @Override
                public void onPageSelected(final int position) {
                    supportInvalidateOptionsMenu();
                    new Thread(new Runnable() {
                        @Override
                        public void run() {
                            switch (position) {
                                case 0:
                                    runOnUiThread(new Runnable() {
                                        @Override
                                        public void run() {
                                            UebersichtTab uebersichtTab = (UebersichtTab) viewPager.getAdapter().instantiateItem(viewPager, 0);
                                            uebersichtTab.refreshLayout.setRefreshing(false);
                                            if(UebersichtTab.refresh == true) {
                                                uebersichtTab.loadUbersicht();
                                                UebersichtTab.refresh = false;
                                            }
                                            activateSideMenu();
                                        }
                                    });

                                    break;
                                case 1:
                                    runOnUiThread(new Runnable() {
                                        @Override
                                        public void run() {
                                            KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                                            if(KalenderTab.refresh == true) {
                                                kalenderTab.loadKalender();
                                                KalenderTab.refresh = false;
                                            }
                                            deactivateSideMenu();
                                        }
                                    });
                                    break;
                                case 2:
                                    deactivateSideMenu();
                                    break;
                                case 3:
                                    deactivateSideMenu();
                                    break;
                            }
                        }
                    }).start();

                }

                @Override
                public void onPageScrollStateChanged(int state) {
                }

            };


            IntentFilter intentFilter = new IntentFilter();
            intentFilter.addAction("com.martinshare.closeActivities");
            brcvr = new BroadcastReceiver() {
                public void onReceive(Context context, Intent intent) {
                    finish();
                }
            };
            registerReceiver(brcvr, intentFilter);

            PushManager.serverCheck(getApplicationContext(), new PushManager());

            MartinshareApiRetro.downloadEintraege(MainActivity.this, getEintraegeProtocol, false);

            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);

            viewPager.setAdapter(adapter);
            viewPager.addOnPageChangeListener(listener);

            viewPager.setOffscreenPageLimit(4);
            tabLayout.setupWithViewPager(viewPager);
            tabLayout.getTabAt(0).setIcon(HC.InvertColor(MainActivity.this, R.drawable.list));
            tabLayout.getTabAt(1).setIcon(HC.InvertColor(MainActivity.this, R.drawable.calendar));
            tabLayout.getTabAt(2).setIcon(HC.InvertColor(MainActivity.this, R.drawable.sun));
            tabLayout.getTabAt(3).setIcon(HC.InvertColor(MainActivity.this, R.drawable.settings));
            tabLayout.setSelectedTabIndicatorColor(getResources().getColor(R.color.grey_white_1000));
            tabLayout.setSelectedTabIndicatorHeight(6);

            menu = new SlidingMenu(MainActivity.this);
            menu.setMode(SlidingMenu.LEFT);
            menu.setShadowWidthRes(R.dimen.shadow_width);
            menu.setShadowDrawable(R.drawable.shadow2);
            menu.setBehindOffsetRes(R.dimen.slidingmenu_offset);
            menu.setBehindScrollScale(1.0f);
            menu.setFadeDegree(0f);
            menu.setTouchModeAbove(SlidingMenu.TOUCHMODE_MARGIN);
            menu.attachToActivity(MainActivity.this, SlidingMenu.SLIDING_CONTENT);
            menu.setBackgroundColor(getResources().getColor(R.color.blue_grey_900));
            menu.setMenu(R.layout.menu);

            activateSideMenu();
            sweetAlertDialog.dismissWithAnimation();
        }
    }

    public void showOverviewFragment(final EintragObj eintragObj) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                newFragment = WholeEintragFragment.newInstance(eintragObj);
                newFragment.show(getSupportFragmentManager(), "eintragOverview");
            }
        });
    }

    private void activateSideMenu() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                getSupportActionBar().setDisplayHomeAsUpEnabled(true);
                menu.setTouchModeAbove(SlidingMenu.TOUCHMODE_FULLSCREEN);
                menu.setSlidingEnabled(true);
            }
        });

    }

    private void deactivateSideMenu() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                getSupportActionBar().setDisplayHomeAsUpEnabled(false);
                menu.setTouchModeAbove(SlidingMenu.TOUCHMODE_FULLSCREEN);
                menu.setSlidingEnabled(false);
            }
        });

    }


    @Override
    protected void onDestroy() {
        super.onDestroy();
        this.unregisterReceiver(brcvr);
        pDialog.dismiss();
        mToast.cancel();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        switch (viewPager.getCurrentItem()) {
            case 0:
                getSupportActionBar().setTitle("Übersicht");
                getSupportActionBar().setSubtitle(String.valueOf(Prefs.getUsername(this)));
                getMenuInflater().inflate(R.menu.menu_main, menu);
                menu.getItem(0).setIcon(R.drawable.refresh);
                break;
            case 1:
                KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                getSupportActionBar().setTitle(getResources().getStringArray(R.array.month_names)[kalenderTab.selectedDate.getMonthOfYear()]);
                getSupportActionBar().setSubtitle(kalenderTab.selectedDate.toString("EE, d MMM yyyy"));
                getMenuInflater().inflate(R.menu.menu_calendar, menu);
                break;
            case 2:
                getSupportActionBar().setTitle("Pläne");
                getSupportActionBar().setSubtitle("");
                getMenuInflater().inflate(R.menu.menu_empty, menu);
                break;
            case 3:
                getSupportActionBar().setTitle("Einstellungen");
                getSupportActionBar().setSubtitle("");
                getMenuInflater().inflate(R.menu.menu_empty, menu);
                break;
        }
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {


        switch (viewPager.getCurrentItem()) {
            case 0:
                switch (item.getItemId()) {
                    case R.id.refresh:
                        MartinshareApiRetro.downloadEintraege(this, getEintraegeProtocol, true);
                        break;
                    case android.R.id.home:

                        AppPreference.notifications(this, Prefs.getEintrags(this));

                        menu.toggle();
                        break;
                }
                break;
            case 1:
                KalenderTab kalenderTab = (KalenderTab) viewPager.getAdapter().instantiateItem(viewPager, 1);
                if (item.getItemId() == R.id.nextMonthToolbarButton) {
                    kalenderTab.calendarZeit.refreshMonth(1);
                    kalenderTab.handleDayClick(kalenderTab.calendarZeit.dt, null, true);
                } else if (item.getItemId() == R.id.prevMonthToolbarButton) {
                    kalenderTab.calendarZeit.refreshMonth(-1);
                    kalenderTab.handleDayClick(kalenderTab.calendarZeit.dt, null, true);
                } else if (item.getItemId() == R.id.todayToolbarButton) {
                    DateTime now;
                    now = DateTime.now().withTime(0, 0, 0, 0);
                    kalenderTab.calendarZeit.resetTime();
                    kalenderTab.handleDayClick(now, null, true);
                }
                mToast.setText(new DateFormatSymbols().getMonths()[kalenderTab.calendarZeit.dt.getMonthOfYear() - 1] + " " + kalenderTab.calendarZeit.dt.getYear());
                mToast.show();
                break;
            case 2: break;
            case 3: break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent intent) {
        super.onActivityResult(requestCode, resultCode, intent);

        if(resultCode == AKTUALISIERENINTENT ) {
            mToast.setText("Eintrag erfolgreich gespeichert!");
            mToast.show();

            if(newFragment != null && newFragment.isVisible()) {
                System.err.println("NEW FRAGMENT STUFF INNER ABFRAGE");
                newFragment.dismissAllowingStateLoss();
            }

            if(menu.isMenuShowing()) {
                menu.toggle();
            }

            MartinshareApiRetro.downloadEintraege(this, getEintraegeProtocol, false);

        }
    }

    public class PagerAdapter extends FragmentStatePagerAdapter {
        int mNumOfTabs;

        public PagerAdapter(FragmentManager fm, int NumOfTabs) {
            super(fm);
            this.mNumOfTabs = NumOfTabs;
        }

        @Override
        public Fragment getItem(int position) {

            switch (position) {
                case 0:
                    UebersichtTab tab1 = new UebersichtTab();
                    return tab1;
                case 1:
                    KalenderTab tab2 = new KalenderTab();
                    return tab2;
                case 2:
                    PlaeneTab tab3 = new PlaeneTab();
                    return tab3;
                case 3:
                    EinstellungenTab tab4 = new EinstellungenTab();
                    return tab4;
                default:
                    return null;
            }

        }

        @Override
        public int getCount() {
            return mNumOfTabs;
        }
    }

}

