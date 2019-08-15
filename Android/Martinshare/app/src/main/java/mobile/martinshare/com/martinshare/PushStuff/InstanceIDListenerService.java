package mobile.martinshare.com.martinshare.PushStuff;

import mobile.martinshare.com.martinshare.Prefs;

/**
 * Created by
 * Modestas Valauskas on 03.10.2015.
 */
public class InstanceIDListenerService extends com.google.android.gms.iid.InstanceIDListenerService {

    @Override
    public void onTokenRefresh() {
        // Fetch updated Instance ID token and notify our app's server of any changes (if applicable).
        Prefs.savePushRegID(Prefs.standartPushRegID, getApplicationContext());
        Prefs.savePushAllow(Prefs.standartPushAllow, getApplicationContext());
        Prefs.serverHasRightPush(false, getApplicationContext());
        PushManager.serverCheck(getApplicationContext(), new PushManager());
    }
}
