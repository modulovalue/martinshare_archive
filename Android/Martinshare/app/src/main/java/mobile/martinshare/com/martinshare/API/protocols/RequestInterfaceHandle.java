package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 06.04.2015.
 */
public interface RequestInterfaceHandle {

    public void onStartedRequest();
    public void onSucess();
    public void onError(int statusCode);
}
