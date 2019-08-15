package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 03.06.2015.
 */
public interface LoginProtocol {
    public void startedLogingIn();
    public void rightCredentials();
    public void wrongCredentials();
    public void noInternetConnection();
}
