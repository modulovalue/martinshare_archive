package mobile.martinshare.com.martinshare.API.protocols;

import android.content.Context;

/**
 * Created by Modestas Valauskas on 03.10.2015.
 */
public interface IsPushIDUp {

    public void pushIDOK(Context context);
    public void pushIDWRONG(Context context);
    public void pushIDERROR(Context context);

}
