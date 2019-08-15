package mobile.martinshare.com.martinshare.activities.vertretungsplan;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.graphics.Color;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.PagerTabStrip;
import android.support.v4.view.ViewPager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.text.InputType;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Toast;

import butterknife.ButterKnife;
import butterknife.Bind;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.protocols.GetVertretungsplanProtocol;
import mobile.martinshare.com.martinshare.HC;
import mobile.martinshare.com.martinshare.R;

public class Vertretungsplan extends ActionBarActivity implements GetVertretungsplanProtocol {


    private Toolbar toolbar;
    SweetAlertDialog pDialog;
    public VertretungsplanData vertretungsplanData;

    @Bind(R.id.pager) ViewPager viewPager;
    @Bind(R.id.linvertretungslayout) LinearLayout linlayout;
    @Bind(R.id.drawerLayoutver) DrawerLayout drawerLayout;
    PagerTabStrip tabStrip;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_vertretungsplan);

        ButterKnife.bind(this);

        tabStrip = (PagerTabStrip) findViewById(R.id.tabTitle2);
        tabStrip.setDrawFullUnderline(true);
        tabStrip.setTabIndicatorColor(getResources().getColor(R.color.yellow_600));

        viewPager.setOffscreenPageLimit(6);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));


        toolbar  = (Toolbar) findViewById(R.id.app_bar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Vertretungsplan");
        getSupportActionBar().setSubtitle(String.valueOf(Prefs.getUsername(getApplicationContext())));
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);


        MartinshareApiRetro.getVetretungsplan(Prefs.getUsername(getApplicationContext()), Prefs.getKey(getApplicationContext()), this);
    }

    @Override
    protected void onResume() {
        super.onResume();
        getSupportActionBar().setTitle("Vertretungsplan");
    }

    public void refreshContent(final VertretungsplanData vertretungsplanData) {

        if(vertretungsplanData.gibtVertretungsplan()) {

            viewPager.setAdapter(new FragmentPagerAdapter(getSupportFragmentManager()) {

                @Override
                public Fragment getItem(int position) {
                    Fragment fragment = new PlanTab();
                    Bundle args = new Bundle();
                    args.putString("url", vertretungsplanData.getUrls().get(position));
                    fragment.setArguments(args);
                    return fragment;
                }

                @Override
                public int getCount() {
                    return vertretungsplanData.getCountSeiten();
                }

                @Override
                public CharSequence getPageTitle(int position) {
                    int seitenanzahl = 1 + position;
                    return "Seite " + seitenanzahl;
                }
            });
        }
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_vertretungsplan, menu);
        menu.getItem(0).setIcon(HC.InvertColor(getApplicationContext(), R.drawable.ic_action_refresh));
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                finish();
                break;
            case R.id.refresh:
                MartinshareApiRetro.getVetretungsplan(Prefs.getUsername(getApplicationContext()), Prefs.getKey(getApplicationContext()), this);
                break;
            case R.id.setsize:

                final EditText input = new EditText(this);
                input.setInputType(InputType.TYPE_CLASS_NUMBER);
                AlertDialog.Builder builder = new AlertDialog.Builder(this);
                input.setHint(Prefs.getVertretungsplanMarkierungSize(getApplicationContext()));
                builder.setTitle("Größe")
                        .setMessage("Bitte gib eine größe von 10 - 70 ein: ")
                        .setCancelable(false)
                        .setView(input)
                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                int value;
                                if(!input.getText().toString().equals("")) {
                                    if(Integer.parseInt(input.getText().toString()) < 10) {
                                        value = 10;
                                    } else if(Integer.parseInt(input.getText().toString()) > 70) {
                                        value = 70;
                                    } else {
                                        value = Integer.parseInt(input.getText().toString());
                                    }

                                    Prefs.saveVertretungsplanMarkierungSize(Integer.toString(value), getApplicationContext());
                                    Toast.makeText(getApplicationContext(), "Bitte aktualisiere den Vertretungsplan", Toast.LENGTH_SHORT).show();
                                }
                            }
                        })
                        .setNegativeButton("Abbrechen", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int whichButton) {
                                dialog.cancel();
                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();
                break;
            case R.id.settext:
                final EditText input2 = new EditText(this);
                input2.setInputType(InputType.TYPE_TEXT_FLAG_NO_SUGGESTIONS);
                input2.setHint(Prefs.getVertretungsplanMarkierungText(getApplicationContext()));
                AlertDialog.Builder builder2 = new AlertDialog.Builder(this);
                builder2.setTitle("Text")
                        .setMessage("Bitte gib den zu markierenden Text ein: ")
                        .setCancelable(false)
                        .setView(input2)
                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                if(!input2.getText().toString().equals("")) {
                                    Prefs.saveVertretungsplanMarkierungText(input2.getText().toString(), getApplicationContext());
                                    Toast.makeText(getApplicationContext(), "Bitte aktualisiere den Vertretungsplan", Toast.LENGTH_SHORT).show();
                                }
                            }
                        })
                        .setNegativeButton("Abbrechen", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int whichButton) {
                                dialog.cancel();
                            }
                        });
                AlertDialog alert2 = builder2.create();
                alert2.show();
                break;
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void startedGetting() {
        pDialog.setTitleText("Vertretungsplan wird geladen");
        pDialog.setContentText("Bitte warten").setCancelable(false);
        pDialog.show();
    }

    @Override
    public void wrongCredentials() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                refreshContent(new VertretungsplanData());
                Toast.makeText(getApplicationContext(), "Du bist nicht eingeloggt.", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void success(final VertretungsplanData data) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                refreshContent(data);
            }
        });
    }

    @Override
    public void noInternetConnection() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                refreshContent(new VertretungsplanData());
                Toast.makeText(getApplicationContext(), "Es besteht keine Internetverbindung", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void keinePlaeneVorhanden() {

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                new SweetAlertDialog(Vertretungsplan.this, SweetAlertDialog.WARNING_TYPE)
                        .setTitleText("Keine Pläne vorhanden.")
                        .setContentText("Es sind keine Pläne vorhanden \n Versuche es zu einem späteren Zeitpunkt nochmal")
                        .setConfirmText("Ok")
                        .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                            @Override
                            public void onClick(SweetAlertDialog sweetAlertDialog) {
                                finish();
                            }
                        })
                        .show();
            }
        });
    }
}
