package mobile.martinshare.com.martinshare.activities.login;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.Rect;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Vibrator;
import android.support.v4.app.ShareCompat;
import android.view.MotionEvent;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.daimajia.androidanimations.library.Techniques;
import com.daimajia.androidanimations.library.YoYo;

import butterknife.Bind;
import butterknife.ButterKnife;
import butterknife.OnClick;
import cn.pedant.SweetAlert.SweetAlertDialog;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.API.protocols.LoginProtocol;
import mobile.martinshare.com.martinshare.PushStuff.PushManager;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;

public class LoginScreen extends Activity implements LoginProtocol{


    @Bind(R.id.Login_Passwort) EditText pw;
    @Bind(R.id.Login_Username) EditText uname;
    @Bind(R.id.imageView3) ImageView logoImageView;

    SweetAlertDialog pDialog;


    long then = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setContentView(R.layout.activity_loginscreen);
        ButterKnife.bind(this);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));

        super.onCreate(savedInstanceState);

        logoImageView.setOnTouchListener(new View.OnTouchListener() {

            @Override
            public boolean onTouch(View v, MotionEvent event) {
                if(event.getAction() == MotionEvent.ACTION_DOWN){
                    then = (Long) System.currentTimeMillis();
                }
                else if(event.getAction() == MotionEvent.ACTION_UP){
                    if(((Long) System.currentTimeMillis() - then) > 4500){
                        Toast.makeText(LoginScreen.this, "OkayOkay", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }
                return false;
            }
        });

        loginDataExists();

        PushManager.registerInBackground(getApplicationContext(), new PushManager());
    }



    @Override
    protected void onPause() {
        super.onPause();
        finish();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        pDialog.dismiss();
    }

    @OnClick(R.id.button3)
    public void loginUser(View v) {
        MartinshareApiRetro.login(uname.getText().toString(), pw.getText().toString(), Prefs.getKey(getApplicationContext()), getApplicationContext(), this);
    }

    public void kontaktieren(View v) {

        ShareCompat.IntentBuilder builder = ShareCompat.IntentBuilder.from(this);
        builder.setType("message/rfc822");
        builder.addEmailTo("info@martinshare.com");
        builder.setSubject("Info");
        builder.setText("\n \n \n \n Martinshare - Android");
        builder.setChooserTitle("Martinshare E-Mail senden");
        builder.startChooser();

    }


    public void loginDataExists() {

        if(Prefs.getUsername(this).equals(Prefs.standartUsername) && Prefs.getKey(this).equals(Prefs.standartKey)) {

        } else {
            Intent intentlogin = new Intent(getApplicationContext(), MainActivity.class);
            intentlogin.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivityForResult(intentlogin, 0);
            finish();
        }

    }


    @Override
    public void startedLogingIn() {
        pDialog.setTitleText("Du wirst Angemeldet...");
        pDialog.setContentText("Bitte warten").setCancelable(false);
        pDialog.show();
    }

    @Override
    public void rightCredentials() {
        pDialog.hide();
        Intent intentlogin = new Intent(getApplicationContext(), MainActivity.class);
        intentlogin.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivityForResult(intentlogin, 0);
        finish();
    }

    @Override
    public void wrongCredentials() {
        Toast.makeText(getApplicationContext(), "Überprüfe deine Logindaten!", Toast.LENGTH_SHORT).show();

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                YoYo.with(Techniques.Wobble).duration(600).playOn(pw);
                YoYo.with(Techniques.Wobble).duration(600).playOn(uname);
                Vibrator v = (Vibrator) getApplicationContext().getSystemService(Context.VIBRATOR_SERVICE);
                pDialog.hide();
                v.vibrate(new long[]{20, 1, 100, 1}, -1);
            }
        });
    }

    @Override
    public void noInternetConnection() {
        pDialog.hide();
        Toast.makeText(getApplicationContext(), "Keine Internetverbindung", Toast.LENGTH_SHORT).show();
    }

    @Override
    public boolean dispatchTouchEvent(MotionEvent event) {
        if (event.getAction() == MotionEvent.ACTION_DOWN) {
            View v = getCurrentFocus();
            if ( v instanceof EditText) {
                Rect outRect = new Rect();
                v.getGlobalVisibleRect(outRect);
                if (!outRect.contains((int)event.getRawX(), (int)event.getRawY())) {
                    v.clearFocus();
                    InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(v.getWindowToken(), 0);

                }
            }
        }
        return super.dispatchTouchEvent( event );
    }
}
