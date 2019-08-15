package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 03.06.2015.
 */
public interface IsLoggedInProtocol {
    public void startedChecking();
    public void isLoggedIn();
    public void isNotLoggedIn();
    public boolean emptyCredentials( String username, String key);
    public void neverWasLoggedIn();
}
