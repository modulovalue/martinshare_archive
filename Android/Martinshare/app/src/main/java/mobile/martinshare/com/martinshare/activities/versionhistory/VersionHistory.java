package mobile.martinshare.com.martinshare.activities.versionhistory;

import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.DialogFragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Toast;

import butterknife.Bind;
import butterknife.ButterKnife;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.API.protocols.GetVersionHistoryProtocol;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import se.emilsjolander.stickylistheaders.StickyListHeadersListView;


public class VersionHistory extends AppCompatActivity implements GetVersionHistoryProtocol {


    SweetAlertDialog pDialog;

    @Bind(R.id.listVersion)
    StickyListHeadersListView list;

    private Toolbar toolbar;

    public static EintragObj eintragObj = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_version_history);
        ButterKnife.bind(this);


        String username = Prefs.getUsername(getApplicationContext());

        toolbar  = (Toolbar) findViewById(R.id.app_bar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(" Verlauf");
        getSupportActionBar().setSubtitle(String.valueOf(username));

        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);


        MartinshareApiRetro.downloadVersionHistory(getApplicationContext(), this, eintragObj);

    }

    DialogFragment newFragment;

    public void populateOverview(EintraegeList eintraegeOrig) {

        try {

            list.setAdapter(new MyOverviewAdapterVersion(getApplicationContext(), eintraegeOrig, list));

        } catch(NullPointerException e) {
            Toast.makeText(this, "Populate NullPointer ERROR", Toast.LENGTH_SHORT).show();
        }
        pDialog.hide();
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                finish();
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onDestroy() {
        pDialog.dismiss();
        super.onDestroy();
    }

    @Override
    public void startedGetting() {
        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        pDialog.setTitleText("Verlauf wird geladen...");
        pDialog.setContentText("Bitte warten").setCancelable(false);
        pDialog.show();
    }

    @Override
    public void notLoggedIn() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Du bist nicht eingeloogt", Toast.LENGTH_LONG).show();
            }
        });
    }

    @Override
    public void noInternet() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Es besteht keine Internetverbindung", Toast.LENGTH_LONG).show();
            }
        });
    }

    @Override
    public void unknownError(final String text) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Martinshare Meldet: " + text, Toast.LENGTH_LONG).show();
            }
        });

    }

    @Override
    public void gotVersionHistory(final EintraegeList eintraege) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                populateOverview(eintraege);

            }
        });
    }
}
