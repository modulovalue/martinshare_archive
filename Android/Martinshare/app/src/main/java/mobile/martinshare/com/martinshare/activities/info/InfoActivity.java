package mobile.martinshare.com.martinshare.activities.info;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.io.InputStream;

import butterknife.Bind;
import butterknife.ButterKnife;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.R;

/**
 * Created by Modestas Valauskas on 06.06.2015.
 */
public class InfoActivity extends AppCompatActivity {


    private Toolbar toolbar;

    @Bind(R.id.imageView2) ImageView logoImageView;
    long then = 0;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_info);
        ButterKnife.bind(this);


        logoImageView.setOnTouchListener(new View.OnTouchListener() {

            @Override
            public boolean onTouch(View v, MotionEvent event) {
                if(event.getAction() == MotionEvent.ACTION_DOWN){
                    then = (Long) System.currentTimeMillis();
                }
                else if(event.getAction() == MotionEvent.ACTION_UP){
                    if(((Long) System.currentTimeMillis() - then) > 4500){
                        Toast.makeText(InfoActivity.this, "OkayOkay", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }
                return false;
            }
        });


        toolbar  = (Toolbar) findViewById(R.id.app_bar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Info");
        getSupportActionBar().setSubtitle("");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

    }

    public void linkms(View v) {
        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://www.martinshare.com"));
        startActivity(browserIntent);
    }

    public void privacypo(View v) {
        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://www.martinshare.com/datenschutzerkl%C3%A4rung.php"));
        startActivity(browserIntent);
    }

    public void lizenzen(View v) {


        Toast.makeText(InfoActivity.this, Prefs.getPushRegID(getApplicationContext()), Toast.LENGTH_SHORT).show();
        Log.e("TAG", Prefs.getUsername(getApplicationContext()));
        Log.e("TAG", Prefs.getPushRegID(getApplicationContext()));
        Log.e("TAG", Prefs.getKey(getApplicationContext()));

        LayoutInflater inflater = LayoutInflater.from(this);
        View view=inflater.inflate(R.layout.scrollviewdialog, null);

        TextView textview=(TextView)view.findViewById(R.id.lizenzen);


        try {
            InputStream is = getAssets().open("lizenzen.txt");

            int size = is.available();

            byte[] buffer = new byte[size];
            is.read(buffer);
            is.close();

            String text = new String(buffer);

            textview.setText(text);
        } catch (IOException e) {

            textview.setText("Error");
        }


        AlertDialog.Builder alertDialog = new AlertDialog.Builder(this)
                    .setTitle("Lizenzen")
                    .setView(view)
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.dismiss();
                        }
                    });
        AlertDialog alert = alertDialog.create();
        alert.show();
    }

    public void debugMartinshare(View v) {

        Toast.makeText(InfoActivity.this, Prefs.getPushRegID(getApplicationContext()), Toast.LENGTH_SHORT).show();
        Log.e("TAG", Prefs.getUsername(getApplicationContext()));
        Log.e("TAG", Prefs.getPushRegID(getApplicationContext()));
        Log.e("TAG", Prefs.getKey(getApplicationContext()));

    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_info, menu);
        return true;
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
}
