package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 21.11.2015.
 */
public interface DeleteEintragProtocol {

    public void startedDeleting();
    public void deleted();
    public void isNotLoggedIn();
    public void noInternet();
    public void unknownError(final String error);
}
