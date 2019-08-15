package mobile.martinshare.com.martinshare.activities.eintragen;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;

import butterknife.Bind;
import butterknife.ButterKnife;
import butterknife.OnClick;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.*;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.POJO.SuggestionPojo;
import mobile.martinshare.com.martinshare.API.protocols.EintragenProtocol;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;


public class Eintragen extends AppCompatActivity implements EintragenProtocol {

    private Toolbar toolbar;
    private String SQLDatum;
    private String anzeigeDatum = "ERROR";
    private String eintragTyp = "";

    @Bind(R.id.nameEintrag) AutoCompleteTextView fachET;
    @Bind(R.id.beschreibungText) EditText beschreibungET;
    @Bind(R.id.artDESpinner) Spinner artDesEintragsSpinner;

    private SweetAlertDialog pDialog;


    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.eintragen_activity);
        ButterKnife.bind(this);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        Intent cal = getIntent();
        Bundle b = cal.getExtras();

        if(b!=null) {
            this.SQLDatum =  b.getString("SQLDatum");
            this.anzeigeDatum = b.getString("anzeigeDatum");
            this.eintragTyp = b.getString("typ");
        }

        System.err.println(getCallingActivity());

        pDialog.setTitleText("Eintrag wird gesendet");
        pDialog.setContentText("Bitte warten").setCancelable(false);

        toolbar  = (Toolbar) findViewById(R.id.app_bar);
        toolbar.setBackgroundColor(getResources().getColor(R.color.White));

        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Neu");
        getSupportActionBar().setSubtitle(anzeigeDatum);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        ArrayList<ItemData> list=new ArrayList<>();
        list.add(new ItemData("Hausaufgabe", R.drawable.ic_hicon));
        list.add(new ItemData("Arbeit", R.drawable.ic_aicon));
        list.add(new ItemData("Sonstiges", R.drawable.ic_sicon));


        fachET.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
            if(hasFocus) {
                MartinshareApiRetro.getNameSuggestions(getApplicationContext(), Eintragen.this, SQLDatum, fachET.getText().toString(), new AutocompleteSuggestions() {
                    @Override
                    public void fill(SuggestionPojo suggestionPojo) {

                    String[] array = new String[suggestionPojo.size()];

                    for (int i = 0; i < array.length; i++) {
                        array[i] = suggestionPojo.getName(i);
                    }

                    ArrayAdapter adapter;
                    adapter = new ArrayAdapter(Eintragen.this, android.R.layout.simple_list_item_1, array);
                    fachET.setAdapter(adapter);
                    fachET.showDropDown();
                    }
                });
            }
            }
        });


        fachET.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if(fachET.length() < 2) {
                    MartinshareApiRetro.getNameSuggestions(getApplicationContext(), Eintragen.this, SQLDatum, fachET.getText().toString(), new AutocompleteSuggestions() {
                        @Override
                        public void fill(SuggestionPojo suggestionPojo) {

                            String[] array = new String[suggestionPojo.size()];

                            for (int i = 0; i < array.length; i++) {
                                array[i] = suggestionPojo.getName(i);
                            }

                            ArrayAdapter adapter = new ArrayAdapter(Eintragen.this, android.R.layout.simple_list_item_1, array);
                            fachET.setAdapter(adapter);
                            fachET.showDropDown();
                        }
                    });
                }
            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });



        SpinnerAdapter adapter2 = new SpinnerAdapter2(this, R.layout.spinner_row_layout, R.id.spinner_text, list);
        artDesEintragsSpinner.setAdapter(adapter2);

        if(eintragTyp.equals("")) {
            artDesEintragsSpinner.setSelection(0);
        } else if (eintragTyp.equals("h")) {
            artDesEintragsSpinner.setSelection(0);
        } else if (eintragTyp.equals("a")) {
            artDesEintragsSpinner.setSelection(1);
        } else if (eintragTyp.equals("s")) {
            artDesEintragsSpinner.setSelection(2);
        }

    }

    protected void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }

    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_eintragen, menu);
        return true;
    }

    public boolean onOptionsItemSelected(MenuItem item) {

        switch (item.getItemId()) {
            case android.R.id.home:
                finish();
                return true;
            case R.id.saveeintrag:
                eintragAbsenden();
                return true;
        }

        return super.onOptionsItemSelected(item);
    }

    public String getSpinnerAuswahl() {
        switch (artDesEintragsSpinner.getSelectedItemPosition()) {
            case 0:
                return "h";
            case 1:
                return "a";
            case 2:
                return "s";
            default: return "e";
        }
    }


    @OnClick(R.id.sendEintrag)
    public void eintragAbsenden() {
        EintragSimple eintrag = new EintragSimple(getSpinnerAuswahl(),
                fachET.getText().toString(),
                beschreibungET.getText().toString(),
                SQLDatum, "0");

        if (eintrag.anythingNullandEmpty()) {
            Toast.makeText(getApplicationContext(), "Bitte fülle alle notwendigen Felder aus!", Toast.LENGTH_LONG).show();
        } else {
            MartinshareApiRetro.neuerEintrag(getApplicationContext(), this, eintrag);
        }
    }


    @Override
    public void startedEintragen() {
        pDialog.show();
    }

    @Override
    public void eingetragen() {
        Intent returnIntent = new Intent();
        setResult(MainActivity.AKTUALISIERENINTENT,returnIntent);
        finish();
    }

    @Override
    public void isNotLoggedIn() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Du bist nicht eingeloggt ", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void noInternet() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                pDialog.hide();
                Toast.makeText(getApplicationContext(), "Es besteht keine Internetverbindung", Toast.LENGTH_SHORT).show();
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

class SpinnerAdapter2 extends ArrayAdapter<ItemData> {

    int groupid;
    ArrayList<ItemData> list;
    LayoutInflater inflater;

    public SpinnerAdapter2(Activity context, int groupid, int id, ArrayList<ItemData> list){
        super(context,id,list);
        this.list=list;
        inflater=(LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.groupid=groupid;
    }

    public View getView(int position, View convertView, ViewGroup parent ){
        View itemView=inflater.inflate(groupid,parent,false);
        ImageView imageView=(ImageView)itemView.findViewById(R.id.spinner_icon);
        imageView.setImageResource(list.get(position).getImageId());
        TextView textView=(TextView)itemView.findViewById(R.id.spinner_text);
        textView.setText(list.get(position).getText());
        return itemView;
    }

    public View getDropDownView(int position, View convertView, ViewGroup parent){
        return getView(position,convertView,parent);
    }

}

class ItemData {

    String text;
    Integer imageId;

    public ItemData(String text, Integer imageId){
        this.text=text;
        this.imageId=imageId;
    }

    public String getText(){
        return text;
    }

    public Integer getImageId(){
        return imageId;
    }
}