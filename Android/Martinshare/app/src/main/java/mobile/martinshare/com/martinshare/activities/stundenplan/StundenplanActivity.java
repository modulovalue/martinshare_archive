package mobile.martinshare.com.martinshare.activities.stundenplan;

import android.graphics.Bitmap;
import android.graphics.Color;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.ImageView;

import butterknife.ButterKnife;
import butterknife.Bind;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.protocols.RequestInterfaceHandle;
import mobile.martinshare.com.martinshare.HC;
import mobile.martinshare.com.martinshare.R;
import uk.co.senab.photoview.PhotoViewAttacher;


public class StundenplanActivity extends ActionBarActivity implements RequestInterfaceHandle {

    @Bind(R.id.imageView)ImageView mImageView;
    SweetAlertDialog pDialog;

    private Toolbar toolbar;
    PhotoViewAttacher mAttacher;

    public static String STUNDENPLANIMAGENAME = "stundenplan";

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stundenplan);

        ButterKnife.bind(this);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        toolbar  = (Toolbar) findViewById(R.id.app_bar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Stundenplan");
        getSupportActionBar().setSubtitle(String.valueOf(Prefs.getUsername(getApplicationContext())));
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        mAttacher = new PhotoViewAttacher(mImageView);

        if(ImageStorage.checkifImageExists(STUNDENPLANIMAGENAME)) {

            Bitmap d = ImageStorage.getBitmapImage(STUNDENPLANIMAGENAME);
            float nh = ( d.getHeight() / 2048f );
            Bitmap scaled = Bitmap.createScaledBitmap(d, (int) (d.getWidth() / nh), 2048, true);

            mImageView.setImageBitmap(scaled);
        } else {
            MartinshareApiRetro.getStundenPlan(mImageView, getApplicationContext(), this);
        }
        mAttacher.update();
    }

    @Override
    public void onStartedRequest() {
        pDialog.setTitleText("Stundenplan wird geladen");
        pDialog.setContentText("Bitte warten").setCancelable(false);
        pDialog.show();
    }

    @Override
    public void onSucess() {
        pDialog.hide();
    }

    @Override
    public void onError(int statusCode) {
        pDialog.hide();
        Prefs.handleError(getApplicationContext(), statusCode);

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_stundenplan, menu);
        menu.getItem(0).setIcon(HC.InvertColor(getApplicationContext(), R.drawable.ic_action_refresh));
        return true;
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        switch (item.getItemId()) {
            case android.R.id.home:
                finish();
                break;
            case R.id.refresh:
                MartinshareApiRetro.getStundenPlan(mImageView, getApplicationContext(), this);
                mAttacher.update();
                break;
        }

        return super.onOptionsItemSelected(item);
    }

}
