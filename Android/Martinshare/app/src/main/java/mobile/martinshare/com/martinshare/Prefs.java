package mobile.martinshare.com.martinshare;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.widget.Toast;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;


public class Prefs {


    public static final String PREF_FILE_NAME = "MartinsharePrefs";

    public static final int NOINTERNET = 0;
    public static final int NOTCHANGED = 304;
    public static final int NOTLOGGEDIN =  403;

    public static String standartUsername = "Nicht eingeloggt";
    public static void saveUsername(String username, Context context) {
        openPrefs(context).putString("Username", username).apply();
    }
    public static String getUsername(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("Username", standartUsername);
    }



    public static String standartKey = "Error";
    public static void saveKey(String key, Context context) {
        openPrefs(context).putString("Key", key).apply();
    }
    public static String getKey(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("Key", standartKey);
    }


    public static String standartLastChanged = "0000-00-00 00:00:00";
    public static void saveLastChanged(String letztesDatum, Context context) {
        openPrefs(context).putString("Lastchanged", letztesDatum).apply();
    }
    public static String getLastChanged(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("Lastchanged", standartLastChanged);
    }



    public static String standartSize = "30";
    public static void saveVertretungsplanMarkierungSize(String size, Context context) {
        openPrefs(context).putString("vertretungsplanMarkierungSize", size).apply();
    }
    public static String getVertretungsplanMarkierungSize(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("vertretungsplanMarkierungSize", standartSize);
    }




    public static String standartText = "Klasse";
    public static void saveVertretungsplanMarkierungText(String text, Context context) {
        openPrefs(context).putString("vertretungsplanMarkierungText", text).apply();
    }
    public static String getVertretungsplanMarkierungText(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("vertretungsplanMarkierungText", standartText);
    }



    // --------------------- PUSH

    // If push allowed
    public static Boolean standartPushAllow = true;
    public static void savePushAllow(Boolean pushAllow, Context context) {
        openPrefs(context).putBoolean("pushAllow", pushAllow).apply();
    }

    public static Boolean getPushAllow(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getBoolean("pushAllow", standartPushAllow);
    }


    //If server has the Same Push
    public static void serverHasRightPush(Boolean pushAllow, Context context) {
        openPrefs(context).putBoolean("serverHasPush", pushAllow).apply();
    }

    public static Boolean hasServerRightPush(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getBoolean("serverHasPush", false);
    }


    //Push Registration ID
    public static String standartPushRegID = "NA";
    public static void savePushRegID(String pushRegID, Context context) {
        openPrefs(context).putString("pushRegID", pushRegID).apply();
    }
    public static String getPushRegID(Context context) {
        return context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE).getString("pushRegID", standartPushRegID);
    }


    // ----------------------------




    public static EintraegeList eintragObjs;

    public static EintraegeList getEintrags(Context context) {
        EintraegeList eintraegeList = Prefs.readObjectFromFile(context, EintraegeList.fileName);
        return eintraegeList != null ? eintraegeList : new EintraegeList();
    }

    public static void witeObjectToFile(Context context, EintraegeList object, String filename) {


        ObjectOutputStream objectOut = null;
        try {

            FileOutputStream fileOut = context.openFileOutput(filename, Activity.MODE_PRIVATE);
            objectOut = new ObjectOutputStream(fileOut);
            objectOut.writeObject(object);
            fileOut.getFD().sync();

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            if (objectOut != null) {
                try {
                    objectOut.close();
                } catch (IOException e) {
                    // do nowt

                    e.printStackTrace();
                }
            }
        }
    }


    /**
     *
     * @param context
     * @param filename
     * @return
     */
    public static EintraegeList readObjectFromFile(Context context, String filename) {

        ObjectInputStream objectIn = null;
        EintraegeList object = null;
        try {

            FileInputStream fileIn = context.getApplicationContext().openFileInput(filename);
            objectIn = new ObjectInputStream(fileIn);
            object = (EintraegeList) objectIn.readObject();

        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        } finally {
            if (objectIn != null) {
                try {
                    if(object == null) object = new EintraegeList();
                    objectIn.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }

        return object;
    }




    // -----------------------------


    public static SharedPreferences.Editor openPrefs(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PREF_FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.edit();
    }

    public static void handleError(Context c, int statusCode) {
        if(statusCode == Prefs.NOINTERNET) {
            Toast.makeText(c, "Keine Internetverbindung", Toast.LENGTH_SHORT).show();

        } else if(statusCode == Prefs.NOTLOGGEDIN) {
            Toast.makeText(c, "Du bist nicht eingeloggt?", Toast.LENGTH_SHORT).show();

        } else {
            Toast.makeText(c, "Ein Fehler ist aufgetreten" + statusCode, Toast.LENGTH_SHORT).show();
        }
    }







}
