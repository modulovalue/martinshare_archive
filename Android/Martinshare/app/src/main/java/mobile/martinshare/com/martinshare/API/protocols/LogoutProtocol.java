package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 05.06.2015.
 */
public interface LogoutProtocol {
    public void startedLogout();
    public void loggedOut();
    public void noInternetConnection();
    public void unknownError();
}
