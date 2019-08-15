package mobile.martinshare.com.martinshare.API.protocols;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;

/**
 * Created by Modestas Valauskas on 24.11.2015.
 */
public interface GetVersionHistoryProtocol {
    public void startedGetting();
    public void notLoggedIn();
    public void noInternet();
    public void unknownError(String text);
    public void gotVersionHistory(EintraegeList eintraege);
}
