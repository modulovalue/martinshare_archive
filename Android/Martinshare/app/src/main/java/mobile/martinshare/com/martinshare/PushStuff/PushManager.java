package mobile.martinshare.com.martinshare.PushStuff;

import android.content.Context;
import android.os.AsyncTask;

import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.android.gms.iid.InstanceID;

import mobile.martinshare.com.martinshare.API.MartinshareApiRetro;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.API.protocols.IsPushIDUp;
import mobile.martinshare.com.martinshare.R;

/**
 * Created by Modestas Valauskas on 03.10.2015.
 */
public class PushManager implements IsPushIDUp  {


    @Override
    public void pushIDOK(Context context) {
        PushManager.serverHasRightPushID(true, context);
    }

    @Override
    public void pushIDWRONG(Context context) {
        PushManager.serverHasRightPushID(true, context);
    }

    @Override
    public void pushIDERROR(Context context) {

    }

    public static void registerInBackground(final Context context, final IsPushIDUp isPushIDUp) {
        new AsyncTask<Void, Void, String>() {
            @Override
            protected String doInBackground(Void... params) {
                InstanceID instanceID = InstanceID.getInstance(context);
                String regid = "";
                try {
                    regid = instanceID.getToken(context.getString(R.string.projectid),
                            GoogleCloudMessaging.INSTANCE_ID_SCOPE, null);
                } catch (Exception e) {

                }
                return regid;
            }

            @Override
            protected void onPostExecute(String msg) {
                Prefs.savePushRegID(msg, context);

                PushManager.serverCheck(context, isPushIDUp);
            }

        }.execute(null, null, null);
    }

    public static void deleteReg(Context context) {
        Prefs.savePushRegID(Prefs.standartPushRegID, context);
    }


    //TODO, SOMETIME IN THE FUTURE, DONT ALLOW PUSH
    public static void allowPush(boolean allow, Context context) {
        Prefs.savePushAllow(allow, context);
    }

    public static void serverHasRightPushID(boolean bool, Context context) {
        Prefs.serverHasRightPush(bool, context);

    }

    public static void serverCheck(Context context, IsPushIDUp isPushIDUp) {
        if(Prefs.getPushRegID(context).equals(Prefs.standartPushRegID)) {
            registerInBackground(context, isPushIDUp);

        } else if(!Prefs.hasServerRightPush(context)){
            MartinshareApiRetro.checkPush(Prefs.getUsername(context), Prefs.getKey(context), Prefs.getPushRegID(context), context, isPushIDUp);
        }

    }


}
