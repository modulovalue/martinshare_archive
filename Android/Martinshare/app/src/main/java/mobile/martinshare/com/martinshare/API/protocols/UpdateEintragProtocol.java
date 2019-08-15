package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 06.06.2015.
 */
public interface UpdateEintragProtocol {
    public void startedUpdating();
    public void upgedatet();
    public void isNotLoggedIn();
    public void noInternet();
    public void unknownError(final String error);
}
