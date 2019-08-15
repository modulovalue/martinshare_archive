package mobile.martinshare.com.martinshare.activities.update;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.DialogFragment;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.protocols.UpdateEintragProtocol;
import mobile.martinshare.com.martinshare.activities.EintragObj;

import butterknife.ButterKnife;
import butterknife.Bind;
import cn.pedant.SweetAlert.SweetAlertDialog;

import mobile.martinshare.com.martinshare.HC;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.eintragen.EintragSimple;

public class UpdateEintraege extends AppCompatActivity implements DatePickerFragment.DateFragment, UpdateEintragProtocol {

    @Bind(R.id.nameEintrag) EditText fachET;
    @Bind(R.id.beschreibungText) EditText beschreibungET;
    @Bind(R.id.dateText) Button dateButton;

    private String SQLDate;
    private int day,month,year;

    private EintragObj eintrag;

    SweetAlertDialog pDialog;

    @Bind(R.id.eintragImageUpdate) ImageView imageView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.update_eintraege_activity);
        ButterKnife.bind(this);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        Toolbar toolbar = (Toolbar) findViewById(R.id.app_bar);
        toolbar.setBackgroundColor(getResources().getColor(R.color.White));

        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Aktualisieren");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        this.eintrag = getIntent().getExtras().getParcelable("eintrag");

        dateButton.setText(eintrag.datumToString());
        this.day   = eintrag.getDatum().getDayOfMonth();
        this.month = eintrag.getDatum().getMonthOfYear();
        this.year  = eintrag.getDatum().getYear();

        this.SQLDate = eintrag.getDatum().getYear() +"-"+ HC.addlead0(eintrag.getDatum().getMonthOfYear()) +"-"+ HC.addlead0(eintrag.getDatum().getDayOfMonth());
        imageView.setImageResource(eintrag.getImage());
        beschreibungET.setText(eintrag.getBeschreibung());
        fachET.setText(eintrag.getName());

    }

    @Override
    public void setDate(int day, int month, int year) {
        this.day = day;
        this.month = month;
        this.year = year;
        this.SQLDate = year +"-"+ HC.addlead0(month) +"-"+ HC.addlead0(day);
        dateButton.setText(day + "." + month + "." + year);
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }

    public void datumAendern(View view) {
        DialogFragment newFragment = new DatePickerFragment().newInstance(day, month-1, year);
        newFragment.show(getSupportFragmentManager(), "datePicker");
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_eintragaendern, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if(item.getItemId() == android.R.id.home) {
            finish();
            return true;
        } else if (item.getItemId() == R.id.saveeintrag) {
            eintragUpdaten();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    public void eintragUpdatenButton(View view) {
        eintragUpdaten();
    }

    public void eintragUpdaten() {

        EintragSimple eintrag2 = new EintragSimple(fachET.getText().toString(),
                beschreibungET.getText().toString(),
                eintrag.getId(),
                SQLDate,
                eintrag.getTyp(),
                "anders", "0");


         if(eintrag2.getDatum() != null && !eintrag2.getDatum().isEmpty() &&
             eintrag2.getFach() != null && !eintrag2.getFach().isEmpty() ) {
             MartinshareApiRetro.updateEintrag(getApplicationContext(), this, eintrag2);
        } else {
            Toast.makeText(getApplicationContext(), "Bitte fülle alle notwendigen Felder aus!", Toast.LENGTH_LONG).show();
        }
    }

    @Override
    public void startedUpdating() {
        pDialog.setTitleText("Eintrag wird aktualisiert");
        pDialog.setContentText("Bitte warten").setCancelable(false);
        pDialog.show();
    }

    @Override
    public void upgedatet() {
        Intent returnIntent = new Intent();
        setResult(MainActivity.AKTUALISIERENINTENT, returnIntent);
        finish();
    }

    @Override
    public void isNotLoggedIn() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Du bist nicht eingeloogt ", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void noInternet() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Es besteht keine Internetverbindung ", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void unknownError(final String error) {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), error, Toast.LENGTH_SHORT).show();
            }
        });
    }
}
