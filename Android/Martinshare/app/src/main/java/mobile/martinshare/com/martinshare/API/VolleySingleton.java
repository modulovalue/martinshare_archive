package mobile.martinshare.com.martinshare.API;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.Volley;

import mobile.martinshare.com.martinshare.MyApplication;

/**
 * Created by Modestas Valauskas on 06.03.2015.
 */
public class VolleySingleton {
    private static VolleySingleton sInstance = null;

    private RequestQueue mRequestQueue;

    private VolleySingleton() {
        mRequestQueue = Volley.newRequestQueue(MyApplication.getAppContext());
    }

    public static VolleySingleton getInstance() {
        if(sInstance == null) {
            sInstance = new VolleySingleton();
        }

        return sInstance;
    }

    public RequestQueue getRequestQueue() {
        return mRequestQueue;
    }
}
