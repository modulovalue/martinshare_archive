package mobile.martinshare.com.martinshare.activities.MainView.EinstellungenTab;


import android.app.*;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.BitmapFactory;
import android.media.RingtoneManager;
import android.os.Bundle;
import android.preference.PreferenceActivity;
import android.support.v4.app.NotificationCompat;
import android.support.v7.preference.PreferenceManager;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.LinearLayout;
import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;
import mobile.martinshare.com.martinshare.Prefs;
import mobile.martinshare.com.martinshare.PushStuff.GcmListenerService;
import mobile.martinshare.com.martinshare.R;
import mobile.martinshare.com.martinshare.activities.EintragObj;
import mobile.martinshare.com.martinshare.activities.MainView.MainActivity;
import mobile.martinshare.com.martinshare.activities.MainView.NotificationPublisher;
import org.joda.time.DateTime;

import java.util.HashMap;
import java.util.Map;

public class AppPreference extends PreferenceActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        addPreferencesFromResource(R.xml.preferences);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);

        LinearLayout root = (LinearLayout)findViewById(android.R.id.list).getParent().getParent().getParent();
        Toolbar bar = (Toolbar) LayoutInflater.from(this).inflate(R.layout.settings_toolbar, root, false);
        root.addView(bar, 0); // insert at top
        bar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }



    public static void notifications(Activity a, EintraegeList eintraegeList) {
        NotificationManager mNotificationManager = (NotificationManager) a.getSystemService(Context.NOTIFICATION_SERVICE);
        mNotificationManager.cancelAll();

        HashMap<DateTime, NotificationScheduleContainer> notificationSchedulerDictionary = new HashMap<>();

        EintraegeList ein = Prefs.getEintrags(a);

        DateTime dateNow = new DateTime().withTime(0,0,0,0);


        //Notification.Builder builderr = new Notification.Builder(a);
        //builderr.setContentTitle("Scheduled Notification");
        //builderr.setContentText("please run man");
        //builderr.setSmallIcon(R.drawable.ic_launcher);
        //
        //
        //NotificationCompat.Builder builde = new NotificationCompat.Builder(a);
        //builde.setContentTitle("Scheduled Notification");
        //builde.setContentText("wwwww");
        //builde.setSmallIcon(R.drawable.ic_launcher);

        //final DateTime dt = new DateTime().withTime(23,26,15,0);
        //
        //final Calendar calendar = Calendar.getInstance();
        //calendar.setTimeInMillis(System.currentTimeMillis());
        //calendar.set(Calendar.HOUR_OF_DAY, 25);
        //calendar.set(Calendar.MINUTE, 27);
        //calendar.set(Calendar.SECOND, 0);
        //calendar.set(Calendar.MILLISECOND, 0);


        //Timer timer = new Timer();
        //timer.scheduleAtFixedRate(new TimerTask() {
        //
        //    @Override
        //    public void run() {
        //        System.err.println("--------------");
        //        System.err.println("dt  " + dt.getMillis());
        //        System.err.println("cal " + calendar.getTimeInMillis());
        //        System.err.println("and " + System.currentTimeMillis());
        //        System.err.println("--------------");
        //    }
        //
        //}, 0, 1000);


        // System.err.println(calendar.getTimeInMillis() +  "    " + dt.getMillis() );
        //scheduleNotification(a, builderr.build(), dt.getMillis());
        //scheduleNotification(a, builde.build(), calendar.getTimeInMillis());


        //PendingIntent contentIntent2 = PendingIntent.getActivity(a, 0, new Intent(a, MainActivity.class), 0);
        //
        //NotificationCompat.Builder builder1 = new NotificationCompat.Builder(a)
        //        .setSmallIcon(GcmListenerService.getNotificationIcon())
        //        .setLargeIcon(BitmapFactory.decodeResource(a.getResources(), R.drawable.ic_action_about))
        //        .setPriority(NotificationCompat.PRIORITY_MAX)
        //        .setContentIntent(contentIntent2)
        //        .setStyle(new NotificationCompat.BigTextStyle()
        //                .bigText(" ausaufgabe(n)")
        //                .setBigContentTitle("Morgen")
        //                .setSummaryText("MMM yyyy"));


       // noti.flags |= Notification.FLAG_AUTO_CANCEL | Notification.FLAG_ONLY_ALERT_ONCE | Notification.FLAG_SHOW_LIGHTS ;

       // long lo = System.currentTimeMillis() - firedate.getMillis();
       // System.err.println( firedate.toString() + "  " + lo);

        //scheduleNotification(a, builde.build(), 5000);




        for(EintragObj eintrag : ein) {
            DateTime date = eintrag.getDatum();

            NotificationScheduleContainer nsc =  notificationSchedulerDictionary.get(date);

            long now = dateNow.getMillis();
            long datel = date.getMillis();

            if( eintrag.isDeletable() && datel >= now) {
                if(nsc != null) {
                    nsc.addToNSC(eintrag.getTyp());
                } else {
                    notificationSchedulerDictionary.put(date, new NotificationScheduleContainer().addToNSC(eintrag.getTyp()));
                }
            }
        }



        SharedPreferences SP = PreferenceManager.getDefaultSharedPreferences(a);
        boolean notif1allow = SP.getBoolean("preference_check_benachrichtigung1", true);
        boolean notif2allow = SP.getBoolean("preference_check_benachrichtigung2", true);
        DateTime notif1time = new DateTime().withMillis(SP.getLong("timePref1", 1476108000000L));
        DateTime notif2time = new DateTime().withMillis(SP.getLong("timePref2", 1476108000000L));

        if(notif1allow) {

            for(Map.Entry<DateTime, NotificationScheduleContainer> entry : notificationSchedulerDictionary.entrySet()) {

                DateTime firedate = new DateTime().withDate(entry.getKey().getYear(), entry.getKey().getMonthOfYear(), entry.getKey().getDayOfMonth()).withTime(notif1time.getHourOfDay(), notif1time.getMinuteOfHour(), 0, 0).minusDays(1);

                if(firedate.isAfter(DateTime.now())) {

                    System.err.println("SCHEDULE: " + entry.getKey().toString() + " AND FIRE " + firedate.toString());

                    PendingIntent contentIntent = PendingIntent.getActivity(a, 0, new Intent(a, MainActivity.class), 0);

                    NotificationCompat.Builder builder = new NotificationCompat.Builder(a)
                            .setSmallIcon(GcmListenerService.getNotificationIcon())
                            .setLargeIcon(BitmapFactory.decodeResource(a.getResources(), R.drawable.ic_action_about))
                            .setPriority(NotificationCompat.PRIORITY_MAX)
                            .setContentIntent(contentIntent)
                            .setStyle(new NotificationCompat.BigTextStyle()
                                    .bigText(entry.getValue().countA + " Arbeit(en), " + entry.getValue().countS + " Sonstiges, " + entry.getValue().countH + " Hausaufgabe(n)")
                                    .setBigContentTitle("Morgen")
                                    .setSummaryText(entry.getKey().toString("EEEE, dd MMMM yyyy")));

                    builder.setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION));

                    Notification noti = builder.build();

                    noti.flags |= Notification.FLAG_AUTO_CANCEL | Notification.FLAG_ONLY_ALERT_ONCE | Notification.FLAG_SHOW_LIGHTS ;

                    long lo = System.currentTimeMillis() - firedate.getMillis();
                    System.err.println( firedate.toString() + "  " + lo);

                    scheduleNotification(a, noti, firedate.getMillis());

                }

            }

        }

        if(notif2allow) {

        }

    }

    private static void scheduleNotification(Context context, Notification notification, long delay) {




        Intent notificationIntent = new Intent(context, NotificationPublisher.class);

        notificationIntent.putExtra(NotificationPublisher.NOTIFICATION_ID, 1);
        notificationIntent.putExtra(NotificationPublisher.NOTIFICATION, notification);

        PendingIntent pendingIntent = PendingIntent.getBroadcast(context, 0, notificationIntent, PendingIntent.FLAG_UPDATE_CURRENT);


        AlarmManager alarmManager = (AlarmManager)context.getSystemService(Context.ALARM_SERVICE);
        alarmManager.set(AlarmManager.RTC_WAKEUP, delay, pendingIntent);
    }

}

