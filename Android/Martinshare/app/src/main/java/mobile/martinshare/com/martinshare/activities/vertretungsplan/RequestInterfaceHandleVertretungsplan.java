package mobile.martinshare.com.martinshare.activities.vertretungsplan;

/**
 * Created by Modestas Valauskas on 11.04.2015.
 */
public interface RequestInterfaceHandleVertretungsplan {

    public void onStartedRequest();
    public void onSucess( VertretungsplanData data);
    public void onError(int statusCode);

}
