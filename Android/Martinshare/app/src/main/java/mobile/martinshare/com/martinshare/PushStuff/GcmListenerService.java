package mobile.martinshare.com.martinshare.PushStuff;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.BitmapFactory;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;


import java.util.Date;

import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;

/**
 * Created by Modestas Valauskas on 03.10.2015.
 */
public class GcmListenerService extends com.google.android.gms.gcm.GcmListenerService {

    public static boolean shouldAktualisierenMainActivity = false;


    @Override
    public void onMessageReceived(String from, Bundle data) {

        NotificationManager mNotificationManager = (NotificationManager) this.getSystemService(Context.NOTIFICATION_SERVICE);

        shouldAktualisierenMainActivity = true;

        String typstring = data.getString("typ");
        String message = data.getString("message");
        String title = data.getString("title");
        String summary = data.getString("summary");

        int icon;

        if( typstring != null && typstring.equals("h")) {
            icon = R.drawable.ic_hicon;
        } else if(typstring != null && typstring.equals("a") ) {
            icon = R.drawable.ic_aicon;
        } else if(typstring != null && typstring.equals("s") ) {
            icon = R.drawable.ic_sicon;
        } else {
            icon = R.drawable.ic_launcher;
        }


        long time = new Date().getTime();
        String tmpStr = String.valueOf(time);
        String last4Str = tmpStr.substring(tmpStr.length() - 5);
        int notificationId = Integer.valueOf(last4Str);

        mNotificationManager.notify(notificationId, getNotification(this, title, message, summary, getNotificationIcon(), icon, false));

    }

    public static Notification getNotification(Context context, String title, String message, String summary, int largeIcon, int smallicon, boolean sound) {

        PendingIntent contentIntent = PendingIntent.getActivity(context, 0, new Intent(context, MainActivity.class), 0);

        NotificationCompat.Builder builder = new NotificationCompat.Builder(context)
                .setContentTitle(title)
                .setContentText(message)
                .setSmallIcon(smallicon)
                .setLargeIcon(BitmapFactory.decodeResource(context.getResources(), largeIcon))
                .setPriority(NotificationCompat.PRIORITY_MAX)
                .setContentIntent(contentIntent)
                .setStyle(new NotificationCompat.BigTextStyle()
                        .bigText(message)
                        .setBigContentTitle(title)
                        .setSummaryText(summary));


        if(sound) {
            builder.setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION));
        }

        Notification noti = builder.build();

        noti.flags |= Notification.DEFAULT_LIGHTS  | Notification.FLAG_AUTO_CANCEL | Notification.FLAG_ONLY_ALERT_ONCE;

        return noti;
    }

    public static int getNotificationIcon() {
        boolean whiteIcon = (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.LOLLIPOP);
        return whiteIcon ? R.drawable.notificationicon : R.drawable.msicon;
    }
}
