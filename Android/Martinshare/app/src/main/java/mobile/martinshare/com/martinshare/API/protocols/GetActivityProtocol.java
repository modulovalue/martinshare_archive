package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 03.06.2015.
 */
public interface GetActivityProtocol {
    public void startedGetting();
    public void notLoggedIn();
    public void noInternet();
    public void unknownError();
    public void aktualisiert(boolean warn, String fetchedData);
}
